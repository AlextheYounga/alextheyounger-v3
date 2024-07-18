
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';


function shaderNormalMaterial() {
    const greyscaleNormalMaterial = new THREE.ShaderMaterial({
        vertexShader: `
            varying vec3 vNormal;
            void main() {
                vNormal = normalize(normalMatrix * normal);
                gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
            }
        `,
        fragmentShader: `
            varying vec3 vNormal;
            void main() {
                float grey = (vNormal.x + vNormal.y + vNormal.z) / 3.0;
                grey = (grey + 1.0) / 2.0; // Convert from [-1,1] to [0,1]
                gl_FragColor = vec4(grey, grey, grey, 1.0);
            }
        `,
        side: THREE.DoubleSide
    });

    return greyscaleNormalMaterial
}

export function createStarshipEnterprise(starField) {
    const loader = new GLTFLoader();

    // Add Starship Enterprise
    return loader.load('CAD/enterprise1701.gltf', function (gltf) {
        gltf.scene.scale.set(35, 35, 35)
        const x = (Math.random() - 0.5) * 1000
        const y = (Math.random() - 0.5) * 0
        const z = (Math.random() - 0.5) * 200
        gltf.scene.position.set(x, y, z);
        gltf.scene.rotateX(5.0)

        gltf.scene.traverse((object) => {
            if (object.isMesh) {
                object.material = shaderNormalMaterial()
            }
        });

        starField.add(gltf.scene);
    }, undefined, function (error) {
        console.error(error);
    });
}
