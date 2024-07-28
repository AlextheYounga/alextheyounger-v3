// Import necessary components
import * as THREE from 'three';
import { createStars } from './space/createStars.js';
import { createStarshipEnterprise } from './space/createStarshipEnterprise.js';
import { enableCameraControls } from './cameraControls.js';

const space = {
    starfield: null,
    interactive: false,
}

// Animation loop
function animate() {
    requestAnimationFrame(animate);
    space.starfield.starField.rotation.x += 0.0001;
    space.starfield.starField.rotation.y += 0.0001;
    space.starfield.renderer.render(
        space.starfield.scene,
        space.starfield.camera
    );

    if (space.interactive) {
        space.starfield.controls.update();
    }
}

function createStarField() {
    // Set up the scene, camera, and renderer
    let scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 2000);
    const renderer = new THREE.WebGLRenderer();

    renderer.setSize(window.innerWidth, window.innerHeight);
    const starfieldDiv = document.getElementById('starfield');
    starfieldDiv.appendChild(renderer.domElement);

    // Add stars to the scene
    const stars = 12109 // The smallest prime formed from reverse concatenation of three consecutive composite numbers
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


export const renderStaticStarfield = async () => {
    space.interactive = false;
    if (document.getElementById("starfield")) {
        if (!space.starfield) {
            space.starfield = createStarField(true)
        }
        animate(); // Start the animation
    }
}

export const renderInteractiveStarfield = async () => {
    space.interactive = true;
    if (document.getElementById("starfield")) {
        if (!space.starfield) {
            space.starfield = createStarField(true)
        }

        space.starfield.controls = enableCameraControls(
            space.starfield.camera,
            space.starfield.renderer
        )
        
        animate(); // Start the animation
    }
}


