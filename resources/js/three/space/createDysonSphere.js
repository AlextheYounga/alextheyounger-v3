import * as THREE from 'three';


function createStar() {
    const geometry = new THREE.SphereGeometry(10, 32, 32);  // Radius of 10
    const material = new THREE.MeshStandardMaterial({ color: 0xFFFF00 });
    const star = new THREE.Mesh(geometry, material);
    star.material.emissive = new THREE.Color(0xFFFF00);  // Make it glow
    return star;
}

function createDysonSection() {
    const radius = 15
    const phiStart = 0
    const phiLength = Math.PI * 2
    const thetaStart = 8
    const thetaLength = Math.PI
    const geometry = new THREE.SphereGeometry(radius, 64, 64, phiStart, phiLength, thetaStart, thetaLength);
    const material = new THREE.MeshStandardMaterial({
        color: 0xCCCCCC,
        side: THREE.DoubleSide,  // Render both sides of the triangles
        metalness: 0.6,
        roughness: 0.4
    });
    const section = new THREE.Mesh(geometry, material);
    return section;
}

export function createDysonSphere() {
    const dysonSphere = new THREE.Group();
    const centralStar = createStar();
    // Create two sections of the Dyson Sphere
    const section1 = createDysonSection();  // Full ring
    const section2 = createDysonSection();  // Full ring, rotated
    section2.rotation.x = Math.PI / 2;  // Rotate to perpendicular

    dysonSphere.add(centralStar);
    dysonSphere.add(section1);
    // dysonSphere.add(section2);

    return dysonSphere
}