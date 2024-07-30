// Import necessary components
import * as THREE from 'three';
import { createStars } from './space/createStars.js';
import { createStarshipEnterprise } from './space/createStarshipEnterprise.js';
import { createSolarSystem } from './space/createSolarSystems.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

let space = null;

// Animation loop
function animate() {
    requestAnimationFrame(animate);
    space.starField.rotation.y +=  0.00001;
    space.renderer.render(space.scene, space.camera);
    space.controls.update();
}

function createStarField(scale = 1) {
    // Set up the scene, camera, and renderer
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1300);
    const renderer = new THREE.WebGLRenderer();

    renderer.setSize(window.innerWidth, window.innerHeight);
    const starfieldDiv = document.getElementById('starfield');
    starfieldDiv.appendChild(renderer.domElement);

    // Add stars to the scene
    const stars = 12109 * scale // The smallest prime formed from reverse concatenation of three consecutive composite numbers
    const mediumStars = stars * 0.85;
    const smallStars = stars * 0.105;
    const largeStars = stars * 0.05;
    const giantStars = stars * 0.005;

    const starFieldMedium = createStars(mediumStars, 3);
    const starFieldLarge = createStars(largeStars, 10)
    const starFieldSmall = createStars(smallStars, 2)
    const starFieldGiant = createStars(giantStars, 25)

    const solarSystem = createSolarSystem()

    const starField = new THREE.Group();
    starField.add(starFieldMedium);
    starField.add(starFieldSmall);
    starField.add(starFieldLarge);
    starField.add(starFieldGiant);
    starField.add(solarSystem);
    
    // Add Enterprise to starfield asynchronously
    // We load the Enterprise CAD model here and it's several megs
    createStarshipEnterprise(starField) 
    
    camera.position.z = 2000; // Camera positioning

    scene.add(starField)

    return {
        starField,
        scene,
        renderer,
        camera,
    }
}

function enableCameraControls(camera, renderer) {
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
    controls.dampingFactor = 0.05;
    controls.screenSpacePanning = false;
    controls.minDistance = 10;
    controls.maxDistance = 2000;
    controls.maxPolarAngle = Math.PI / 2;

    return controls
}

export const renderStarfield = async () => {
    if (document.getElementById("starfield")) {
        if (!space) {
            space = createStarField()
        }

        space.controls = enableCameraControls(
            space.camera,
            space.renderer
        )

        animate(); // Start the animation
    }
}


