import * as THREE from 'three';


export function createOrbitalSystems(count, scene, renderer) {
    for (let i = 0; i < count; i++) {
        const starSize = 3.5;
        const x = Math.random() * 2000;
        const y = Math.random() * 2000;
        const z = Math.random() * 6000;
        const starPosition = new THREE.Vector3(x, y, z);
        const orbitGroup = new THREE.Group();

        // Create the central star
        const starGeometry = new THREE.SphereGeometry(starSize, 32, 32);
        const starMaterial = new THREE.MeshBasicMaterial({ color: 0xffffff });
        const star = new THREE.Mesh(starGeometry, starMaterial);
        star.position.set(starPosition.x, starPosition.y, starPosition.z);
        orbitGroup.add(star);

        // Create an orbiting object
        const planetSize = starSize * 0.25; // smaller than the star
        const planetGeometry = new THREE.SphereGeometry(planetSize, 32, 32);
        const planetMaterial = new THREE.MeshBasicMaterial({ color: 0xff0000 }); // red planet
        const planet = new THREE.Mesh(planetGeometry, planetMaterial);
        const orbitRadius = starSize * 20; // distance from the star
        planet.position.set(starPosition.x + orbitRadius, starPosition.y, starPosition.z);
        orbitGroup.add(planet);

        // Orbit mechanics
        const orbitSpeed = 0.001; // radians per frame
        function animateOrbit() {
            requestAnimationFrame(animateOrbit);
            planet.position.x = starPosition.x + Math.cos(Date.now() * orbitSpeed) * orbitRadius;
            planet.position.y = starPosition.y + Math.cos(Date.now() * orbitSpeed) * orbitRadius;
            planet.position.z = starPosition.z + Math.sin(Date.now() * orbitSpeed) * orbitRadius;
            renderer.render(scene, camera);
        }
        animateOrbit();

        scene.add(orbitGroup);
    }

    return scene
}