
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { createStar } from './createStars';


const createLighting = async (starField, position) => {
    // Add lights to the scene
    let {x,y,z} = position
    x += 20
    y += 20
    z += 20

    const ambientLight = new THREE.AmbientLight(0xffffff);
    const directionalLight = new THREE.DirectionalLight(0xffffff);
    const pointLight = new THREE.PointLight(0xffffff, 5, 30);

    directionalLight.position.set(x,y,z).normalize();
    pointLight.position.set(x,y,z); // Set the position of the light
    
    const color = new THREE.Color(0xffffff);
    const hue = 0.6
    const saturation = 0.5; // Saturation
    const lightness = 0.5; // Lightness
    color.setHSL(hue, saturation, lightness);  // Reapply the modified HSL values

    const star = createStar({x,y,z}, color, 30)

    starField.add(ambientLight);
    starField.add(directionalLight);
    starField.add(pointLight);
    starField.add(star)
}


export async function createStarshipEnterprise(starField) {
    const loader = new GLTFLoader();
    const x = 50
    const y = 0
    const z = 50

    const position = {x,y,z}
    createLighting(starField, position) // Add lighting to the scene

    // Add Starship Enterprise
    return loader.load('cad/Enterprise_Original.gltf', function (enterprise) {
        enterprise.scene.name = 'enterprise'
        enterprise.scene.scale.set(10, 10, 10)
        enterprise.scene.position.set(x, y, z);
        enterprise.scene.rotateX(2)
        enterprise.scene.rotateY(3)
    
        starField.add(enterprise.scene);
        
    }, undefined, function (error) {
        console.error(error);
    });
}