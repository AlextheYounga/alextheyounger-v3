import * as THREE from 'three';
import { FreeFlyControls } from './universe/freeFlyControls.js';
import { SectorManager } from './universe/sectorManager.js';

let universe = null;

const clock = new THREE.Clock();

function animate() {
    requestAnimationFrame(animate);
    const delta = clock.getDelta();

    universe.controls.update(delta);
    universe.sectorManager.update(universe.camera);

    universe.renderer.render(universe.scene, universe.camera);
}

function createUniverse() {
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x000000);

    const camera = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight,
        0.1,
        100000
    );
    camera.position.set(0, 0, 500);

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);

    window.addEventListener('resize', () => {
        renderer.setSize(window.innerWidth, window.innerHeight);
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
    });

    const starfieldDiv = document.getElementById('starfield');
    starfieldDiv.appendChild(renderer.domElement);

    const sectorManager = new SectorManager(scene);
    sectorManager.update(camera);

    return {
        scene,
        camera,
        renderer,
        sectorManager,
    };
}

export const renderStarfield = async () => {
    if (document.getElementById('starfield')) {
        if (!universe) {
            universe = createUniverse();
        }

        if (!universe.controls) {
            universe.controls = new FreeFlyControls(
                universe.camera,
                universe.renderer.domElement
            );
        }

        animate();
    }
};
