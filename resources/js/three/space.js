// Import necessary components
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { createStars } from './_createStars.js';
import { createDysonSwarm } from './_createDysonSwarm.js';

// Set up the scene, camera, and renderer
let scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 2000);
const renderer = new THREE.WebGLRenderer();
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);


// Add stars to the scene
const stars = 12109 // The smallest prime formed from reverse concatenation of three consecutive composite numbers
scene = createStars(stars, scene);
// scene = createDysonSwarm(scene, renderer, camera);

// Camera positioning
camera.position.z = 1000;

// Controls
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
controls.dampingFactor = 0.05;
controls.screenSpacePanning = false;
controls.minDistance = 100;
controls.maxDistance = 500;
controls.maxPolarAngle = Math.PI / 2;

// Animation loop
function animate() {
    requestAnimationFrame(animate);
    // starField.rotation.x += 0.0005;
    // starField.rotation.y += 0.0005;
    renderer.render(scene, camera);
    controls.update();
}

// Start the animation
animate();


