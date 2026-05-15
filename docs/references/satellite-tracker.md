Project Path: Three

Source Tree:

```txt
Three
├── createEarth.js
├── createSatellitePoints.js
├── createStarlinkSatellite.js
├── scene.js
└── sceneHelpers.js

```

`createEarth.js`:

```js
import * as THREE from 'three';
import { loadAsync } from 'jszip';
import { handleTime } from './sceneHelpers.js';

function flatMapCoordinates(input) {
	const result = [];
	function flattenArray(arr) {
		return arr.reduce((acc, val) =>
			Array.isArray(val) ? acc.concat(flattenArray(val)) : acc.concat([val]),
			[]);
	}
	const flattened = flattenArray(input);

	for (let i = 0; i < flattened.length; i += 2) {
		const pair = flattened.slice(i, i + 2);
		if (pair.length === 2) {  // Ensure only valid pairs are included
			result.push(pair);
		}
	}

	return result;
}

export function createEarth() {
	// Create the Earth geometry and material
	const paleBlueDot = new THREE.Group()

	// Load textures
	const textureLoader = new THREE.TextureLoader();
	const earthTexture = textureLoader.load('./images/8081_earthmap10k.jpg.webp');
	const radius = 4;
	const earthGeometry = new THREE.SphereGeometry(radius, 64, 64);
	const earthMaterial = new THREE.MeshPhongMaterial({ map: earthTexture });
	const earthSphere = new THREE.Mesh(earthGeometry, earthMaterial);

	earthSphere.rotateY(Math.PI)
	paleBlueDot.add(earthSphere)

	fetch('/data/geojson.zip').then((response) => {
		loadAsync(response.blob()).then(zip => {
			Object.keys(zip.files).forEach(filename => {
				if (filename.endsWith('.json') && !filename.startsWith('__')) {
					zip.files[filename].async('string').then(content => {
						const jsonContent = JSON.parse(content);

						jsonContent.features.forEach((feature) => {
							const coordinates = flatMapCoordinates(feature.geometry.coordinates);
							const points = coordinates.map(([lon, lat]) => {
								// Ensure valid longitude (-180 to 180) and latitude (-90 to 90)
								if (isNaN(lat) || isNaN(lon) || lat < -90 || lat > 90 || lon < -180 || lon > 180) {
									console.warn(`Invalid coordinate: [${lon}, ${lat}]`);
									return null;  // Skip invalid points
								}

								const phi = (90 - lat) * (Math.PI / 180);  // Latitude to radians
								const theta = (lon + 180) * (Math.PI / 180);  // Longitude to radians

								const x = radius * Math.sin(phi) * Math.cos(theta);
								const y = radius * Math.cos(phi);
								const z = radius * -Math.sin(phi) * Math.sin(theta);

								return new THREE.Vector3(x, y, z);  // Valid Vector3 point
							}).filter(point => point !== null);  // Filter out null points

							// Create a Three.js line from the points
							const geometry = new THREE.BufferGeometry().setFromPoints(points);
							const material = new THREE.LineBasicMaterial({ color: 0xffffff });
							const line = new THREE.Line(geometry, material);

							// Add the line to the scene
							paleBlueDot.add(line);
						});
					});
				}
			})
		})
	})
	
	const timeRadians = handleTime();
	paleBlueDot.rotateY(timeRadians) // Put the earth at noon at UTC time.

	// const axesHelper = new THREE.AxesHelper(5); // Adjust size to fit your scene
	// paleBlueDot.add(axesHelper); // Add axes helper to the Earth


	return paleBlueDot
}
```

`createSatellitePoints.js`:

```js
import * as THREE from 'three';
import { twoline2satrec, propagate } from 'satellite.js';
import { handleTime } from './sceneHelpers.js';

const satelliteMap = {}
const badSatellites = [55394, 55424] // Corrupt data


export async function updateSatellitePositions(earthScene) {
	// Update each particle's position
	const positions = earthScene.satellitePoints.geometry.attributes.position.array;
	const ids = earthScene.satellitePoints.geometry.attributes.id.array;
	const newPositions = []
	for (let i = 0; i < ids.length; i++) {
		const noradId = ids[i];
		const satellite = satelliteMap[noradId];
		const satellitePosition = calculateSatellitePosition(satellite.tle_line1, satellite.tle_line2);
		newPositions.push(satellitePosition)
		positions[i * 3] = satellitePosition.x;
		positions[i * 3 + 1] = satellitePosition.y;
		positions[i * 3 + 2] = satellitePosition.z;
	}
	// const earthRotationAngle = handleTime(); // Get the current rotation angle
	// const rotationMatrix = new THREE.Matrix4().makeRotationY(-earthRotationAngle); // Negative to sync
	// earthScene.satellitePoints.geometry.rotateY(Math.PI)
	// earthScene.satellitePoints.geometry.applyMatrix4(rotationMatrix);
	earthScene.satellitePoints.geometry.attributes.position.needsUpdate = true; // Notify Three.js of the update

	const geometryCenter = new THREE.Vector3(0,0,0);
	const distanceToCenter = earthScene.camera.position.distanceTo(geometryCenter);

	// Update satellite labels
    if (distanceToCenter < 6) {
		for (let i = 0; i < newPositions.length; i++) {
			const position = newPositions[i];
			earthScene.labels[i].visible = true;
			earthScene.labels[i].position.set(position.x, position.y + 0.01, position.z + 0.01);
		}
    } else {
        // Hide all labels when too far
		for (let i = 0; i < ids.length; i++) {
			earthScene.labels[i].visible = false;
		}
    }
}

function calculateSatellitePosition(tleLine1, tleLine2) {
	// The x, y, z coordinates are swapped in the telemetry data
	const toThree = (v) => { return { x: v.x, y: v.z, z: -v.y } };
	const scaleFactor = 4 / 6371; // 0.000627; Scaling factor for my radius of Earth at 4 compared to true radius of Earth
	const satrec = twoline2satrec(tleLine1, tleLine2);
	const telemetry = propagate(satrec, new Date());

	if (!telemetry?.position) return false;

	let { x, y, z } = toThree(telemetry.position);

	// Compute Cartesian coordinates in the orbital plane
	x *= scaleFactor;
	y *= scaleFactor;
	z *= scaleFactor;

	return { x, y, z };
}

function createSpriteTextLabel(text, position) {
    // Create canvas for text
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = 256;
    canvas.height = 256;
    
    // Style text - make font smaller to accommodate smaller sprite size
    context.font = 'Bold 24px Arial';
    context.fillStyle = 'white';
    context.textAlign = 'center';
    context.textBaseline = 'middle';
    context.fillText(text, canvas.width/2, canvas.height/2);
    
    // Create sprite with smaller scale
    const texture = new THREE.CanvasTexture(canvas);
    const spriteMaterial = new THREE.SpriteMaterial({ 
        map: texture,
        sizeAttenuation: true
    });
    const sprite = new THREE.Sprite(spriteMaterial);
    sprite.position.set(position.x, position.y + 0.01, position.z + 0.01);
    sprite.scale.set(0.1, 0.1, 0.1);
	sprite.visible = false;
    
    return sprite;
}


export function createSatellitePoints(satellites) {
	const ids = [];
	const positions = [];
	const labels = [];
	const geometry = new THREE.BufferGeometry();
	const size = 0.005;
	const limit = 10
	let i = 0;

	for (const satellite of satellites) {
		if (i++ >= limit) break; // Limit the number of satellites processed
		const noradId = parseInt(satellite.norad_cat_id)
		if (badSatellites.includes(noradId)) continue; // Skip bad satellites

		const satellitePosition = calculateSatellitePosition(satellite.tle_line1, satellite.tle_line2);
		if (!satellitePosition) continue;

		const label = createSpriteTextLabel(satellite.object_name, satellitePosition);
		labels.push(label)

		ids.push(noradId);
		positions.push(satellitePosition.x, satellitePosition.y, satellitePosition.z);
		satelliteMap[noradId] = satellite; // Store satellite details by ID
	}

	const colors = new Float32Array(positions.length).fill(1.0);
	geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
	geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
	geometry.setAttribute('id', new THREE.Int32BufferAttribute(ids, 1)); // Add custom 'id' attribute
	
	
	// geometry.rotateY(Math.PI)
	// const earthRotationAngle = handleTime(); // Get the current rotation angle
	// const rotationMatrix = new THREE.Matrix4().makeRotationY(-earthRotationAngle); // Negative to sync
	// geometry.applyMatrix4(rotationMatrix);

	const material = new THREE.PointsMaterial({ size: size });
	const points = new THREE.Points(geometry, material);

	return {
		points, 
		labels
	}
}


```

`createStarlinkSatellite.js`:

```js

import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

// TODO: Add real Starlink satellite model to the scene 

export async function createStarlinkSatellite(scene) {
    const loader = new GLTFLoader();
    const x = 2.7
    const y = 2.7
    const z = 2.7

    return loader.load('./gltf/starlink_spacex_satellite/scene.gltf', function (starlink) {
        starlink.scene.name = 'starlink'
        starlink.scene.scale.set(0.01, 0.01, 0.01)
        starlink.scene.position.set(x, y, z);
		starlink.scene.lookAt(0, 0, 0)
		starlink.scene.rotateX(Math.PI / 2)
		starlink.scene.rotateY(Math.PI / 2)
		starlink.scene.rotateZ(Math.PI)

		scene.add(starlink.scene)
    }, undefined, function (error) {
        console.error(error);
    });
}
```

`scene.js`:

```js
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { createEarth } from './createEarth';
import { createSatellitePoints, updateSatellitePositions } from './createSatellitePoints';

// Create the scene
const sceneRenderer = new THREE.WebGLRenderer({ antialias: true });
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 100);

// State variables
let isAnimating = true; // Track if animation is running
let earthScene = null;
const fps = 30; // We put satellite animation sloooow so we don't kill our CPUs
let fpsInterval, now, then, elapsed, start;

function createSun() {
	// Add directional light
	const directionalLight = new THREE.DirectionalLight(0xffffff, 4);
	const ambientLight = new THREE.AmbientLight(0x333333); // Soft lighting
	directionalLight.position.set(5, -1, 0).normalize();

	return {
		directionalLight,
		ambientLight
	}
}

function createPlayButton() {
	// Create Play/Stop Button
	const button = document.createElement('button');
	button.innerHTML = isAnimating ? 'Stop' : 'Play'; // Update button text
	button.style.position = 'absolute';
	button.style.top = '8px';
	button.style.left = '8px';
	button.style.padding = '8px 20px';
	button.style.fontSize = '8px';
	button.style.backgroundColor = '#fff';
	button.style.zIndex = '1000'; // Ensure button is on top
	document.body.appendChild(button);

	// Toggle animation on button click
	button.addEventListener('click', () => {
		isAnimating = !isAnimating; // Toggle the animation state
		button.innerHTML = isAnimating ? 'Stop' : 'Play'; // Update button text
	});
}

function setScene(satellites) {
	// Set up scene renderer
	sceneRenderer.setSize(window.innerWidth, window.innerHeight);
	document.body.appendChild(sceneRenderer.domElement);

	// Handle window resizing
	window.addEventListener('resize', () => {
		sceneRenderer.setSize(window.innerWidth, window.innerHeight);
		camera.aspect = window.innerWidth / window.innerHeight;
		camera.updateProjectionMatrix();
	});

	// Set up controls
	const controls = new OrbitControls(camera, sceneRenderer.domElement);
	controls.screenSpacePanning = false;
	controls.minDistance = 4.7;
	controls.maxDistance = 100;

	// Camera positioning
	camera.position.z = 10;

	// Add the Sun to the scene
	const sun = createSun();
	scene.add(sun.directionalLight);
	scene.add(sun.ambientLight);

	// Add the Earth to the scene, with satellite points 
	const earth = createEarth(satellites);

	// Add the satellite points to the earth scene
	// const satellitePoints = createSatellitePoints(satellites);
	const {points, labels} = createSatellitePoints(satellites);
	for (const label of labels) earth.add(label)
	earth.add(points);
	scene.add(earth)

	return {
		earth,
		satellites,
		controls,
		camera,
		labels,
		satellitePoints: points,
	}
}


function animate() {
	requestAnimationFrame(animate); // Only continue animation if allowed

	now = Date.now();
	elapsed = now - then;

	// Rotate the Earth
	earthScene.earth.rotation.y += 1.21e-6;  // Adjust speed of rotation here
	sceneRenderer.render(scene, camera); // Render the scene
	earthScene.controls.update();

	if (!isAnimating) return; // If not animating, skip satellite updates

	// if enough time has elapsed, draw the next frame
	if (elapsed > fpsInterval) {
		// Let's give it a couple seconds before we worry about animating satellites
		if ((now - start) > 2000) {
			// Get ready for next frame by setting then=now, but also adjust for your
			// specified fpsInterval not being a multiple of RAF's interval (16.7ms)
			then = now - (elapsed % fpsInterval);
			updateSatellitePositions(earthScene);
		}
	}
}

function startAnimating() {
	fpsInterval = 1000 / fps;
	then = Date.now();
	start = then;
	animate();
}

export const renderEarthScene = async (satellites) => {
	earthScene = setScene(satellites)
	createPlayButton(); // Create the play button
	startAnimating(); // Start the animation
}
```

`sceneHelpers.js`:

```js
export function handleTime() {
	// Returns amount of radians in which to rotate scene to orient with sun.
	// UTC Greenwhich Mean happens to be at just about midnight by default, which makes this problem easy.
	// This time is surprisingly accurate.
	
	const now = new Date();
	const secondsInADay = 86400;
	const currentHour = now.getUTCHours();
	const currentMinute = now.getUTCMinutes();
	const currentSecond = now.getUTCSeconds();
	const currentHourSeconds = 3600 * currentHour;
	const currentMinuteSeconds = 60 * currentMinute;
	const timeFraction = (currentHourSeconds + currentMinuteSeconds + currentSecond) / secondsInADay;
	const rotationRadians = timeFraction * Math.PI * 2; // Full rotation in radians

	return rotationRadians
}
```