import * as THREE from 'three';


export function createDysonSwarm(scene, renderer, camera) {
    const starSize = 10;
    const starPosition = new THREE.Vector3(0.1, 0.1, 0.1);
    const orbitGroup = new THREE.Group();

    // Create the central star
    const starGeometry = new THREE.SphereGeometry(starSize, 32, 32);
    const starMaterial = new THREE.MeshBasicMaterial({ color: 0xffffff });
    const star = new THREE.Mesh(starGeometry, starMaterial);
    star.position.set(starPosition.x, starPosition.y, starPosition.z);
    orbitGroup.add(star);
    
    // Create an orbiting swarm node
    const swarmCount = 100;
    for (let i = 0; i < swarmCount; i++) {
        const orbitSpeed = 0.001; // radians per frame
        const orbitRadius = starSize * 1.05; // distance from the star
        const inclination = Math.random() * 10; // Random inclination up to 45 degrees

        const nodeSize = starSize * 0.02; // smaller than the star
        const nodeGeometry = new THREE.SphereGeometry(nodeSize, 32, 32);
        const nodeMaterial = new THREE.MeshBasicMaterial({ color: 'grey' });
        const node = new THREE.Mesh(nodeGeometry, nodeMaterial);
        node.position.set(starPosition.x + orbitRadius, starPosition.y, starPosition.z);
        orbitGroup.add(node);

        function animateOrbit() {
            // Orbital Mechanics
            requestAnimationFrame(animateOrbit);
            const orbitPosition = Date.now() + (i * 1000) // Unique orbit position for each node
            let nodeX = Math.cos(orbitPosition * orbitSpeed) * orbitRadius;
            let nodeY = Math.sin(orbitPosition * orbitSpeed) * orbitRadius * Math.sin(inclination);  // Apply inclination
            let nodeZ = Math.sin(orbitPosition * orbitSpeed) * orbitRadius;
            node.position.x = starPosition.x + nodeX
            node.position.y = starPosition.y + nodeY
            node.position.z = starPosition.z + nodeZ
            renderer.render(scene, camera);
        }

        animateOrbit();
    }

    scene.add(orbitGroup);

    return scene
}