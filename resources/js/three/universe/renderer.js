import * as THREE from 'three';

const textureCache = new Map();

function getStarTexture() {
    if (textureCache.has('star')) return textureCache.get('star');

    const canvas = document.createElement('canvas');
    const size = 128;
    canvas.width = size;
    canvas.height = size;

    const context = canvas.getContext('2d');
    const gradient = context.createRadialGradient(size / 2, size / 2, 0, size / 2, size / 2, size / 2);
    gradient.addColorStop(0.1, 'rgba(255,255,255,1)');
    gradient.addColorStop(0.2, 'rgba(255,255,255,0.8)');
    gradient.addColorStop(0.4, 'rgba(255,255,255,0.5)');
    gradient.addColorStop(1, 'rgba(255,255,255,0)');

    context.fillStyle = gradient;
    context.fillRect(0, 0, size, size);

    const texture = new THREE.CanvasTexture(canvas);
    textureCache.set('star', texture);
    return texture;
}

export function createStarPoints(sector) {
    const positions = [];
    const colors = [];
    const sizes = [];

    const color = new THREE.Color();

    for (const star of sector.stars) {
        positions.push(star.x, star.y, star.z);
        color.setHSL(star.hue, star.saturation, star.lightness);
        colors.push(color.r, color.g, color.b);
        sizes.push(star.size);
    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));
    geometry.setAttribute('size', new THREE.Float32BufferAttribute(sizes, 1));

    const material = new THREE.PointsMaterial({
        size: 5,
        map: getStarTexture(),
        transparent: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
        vertexColors: true,
        sizeAttenuation: true,
    });

    return new THREE.Points(geometry, material);
}

export function createPlanetMesh(planet) {
    const geometry = new THREE.SphereGeometry(planet.radius, 32, 32);
    const material = new THREE.MeshPhongMaterial({ color: planet.color });
    const mesh = new THREE.Mesh(geometry, material);
    mesh.position.set(planet.x, planet.y, planet.z);
    return mesh;
}

export function createStarLight(star) {
    const group = new THREE.Group();

    const pointLight = new THREE.PointLight(0xffffff, 2, 200);
    pointLight.position.set(star.x, star.y, star.z);
    group.add(pointLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
    directionalLight.position.set(star.x, star.y, star.z).normalize();
    group.add(directionalLight);

    return group;
}
