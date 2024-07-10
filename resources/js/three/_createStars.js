import * as THREE from 'three';

function createStarTexture() {
    const canvas = document.createElement('canvas');
    const size = 128; // Size of the texture
    canvas.width = size;
    canvas.height = size;

    const context = canvas.getContext('2d');
    const gradient = context.createRadialGradient(
        size / 2, size / 2, 0,
        size / 2, size / 2, size / 2
    );
    gradient.addColorStop(0, 'rgba(255,255,255,1)');
    gradient.addColorStop(0.2, 'rgba(255,255,255,0.8)');
    gradient.addColorStop(0.4, 'rgba(255,255,255,0.5)');
    gradient.addColorStop(1, 'rgba(255,255,255,0)');

    context.fillStyle = gradient;
    context.fillRect(0, 0, size, size);

    const texture = new THREE.Texture(canvas);
    texture.needsUpdate = true;
    return texture;
}

export function createStars(count, scene) {
    const geometry = new THREE.BufferGeometry();
    const positions = [];
    const colors = [];
    const sizes = []; // Array to store sizes of each star

    for (let i = 0; i < count; i++) {
        positions.push((Math.random() - 0.5) * 2000); // x
        positions.push((Math.random() - 0.5) * 2000); // y
        positions.push((Math.random() - 0.5) * 6000); // z

        // Set a random luminance on a white color
        // Generate a random color biased towards red or blue
        const color = new THREE.Color();
        const isRedshift = Math.random() > 0.5; // Randomly decide if the star is redshifted
        const hue = isRedshift ? 0 : 0.6; // Red (0) or Blue (0.6)
        const saturation = 0.75 + Math.random() * 0.25; // Saturation
        const lightness = 0.5 + Math.random() * 0.5; // Lightness

        color.setHSL(hue, saturation, lightness);  // Reapply the modified HSL values
        colors.push(color.r, color.g, color.b);

        // Assign a slightly varied size
        const size = 1 + Math.random() * 0.5; // Base size of 1, with small random variation
        sizes.push(size);
    }

    geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));
    geometry.setAttribute('size', new THREE.BufferAttribute(new Float32Array(sizes), 1)); // Add size attribute

    const material = new THREE.PointsMaterial({
        size: 3,
        map: createStarTexture(),
        transparent: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
        vertexColors: true,
    });

    const stars = new THREE.Points(geometry, material);
    scene.add(stars)

    return scene
}

