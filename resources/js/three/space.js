// Import necessary components
import * as THREE from 'three';
import { createStars } from './space/createStars.js';
import { createStarshipEnterprise } from './space/createStarshipEnterprise.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

let space = null;

// Animation loop
function animate() {
    requestAnimationFrame(animate);
    space.starField.rotation.x += 0.00005;
    space.starField.rotation.y += 0.00008;
    space.renderer.render(space.scene, space.camera);
    space.controls.update();
}

function createStarField(scale = 1) {
    // Set up the scene, camera, and renderer
    let scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 2000);
    const renderer = new THREE.WebGLRenderer();

    renderer.setSize(window.innerWidth, window.innerHeight);
    const starfieldDiv = document.getElementById('starfield');
    starfieldDiv.appendChild(renderer.domElement);

    // Add stars to the scene
    const stars = 12109 * scale // The smallest prime formed from reverse concatenation of three consecutive composite numbers
    const mediumStars = stars * 0.80;
    const smallStars = stars * 0.145;
    const largeStars = stars * 0.05;
    const giantStars = stars * 0.005;

    const starFieldMedium = createStars(mediumStars, 3);
    const starFieldLarge = createStars(largeStars, 10)
    const starFieldSmall = createStars(smallStars, 2)
    const starFieldGiant = createStars(giantStars, 25)

    const starField = new THREE.Group();
    starField.add(starFieldMedium);
    starField.add(starFieldSmall);
    starField.add(starFieldLarge);
    starField.add(starFieldGiant);

    createStarshipEnterprise(starField) // Add Enterprise to starfield

    scene.add(starField); // Add entire starfield to the scene
    camera.position.z = 2000; // Camera positioning

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
    controls.minDistance = 100;
    controls.maxDistance = 500;
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


