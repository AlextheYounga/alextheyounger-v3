import * as THREE from 'three';
import { generateSector, getSectorKey, worldToSector } from './sectorGenerator.js';
import { createStarPoints, createPlanetMesh, createStarLight } from './renderer.js';

const RENDER_DISTANCE = 1;

export class SectorManager {
    constructor(scene) {
        this.scene = scene;
        this.activeSectors = new Map();
        this.ambientLight = new THREE.AmbientLight(0x222222);
        scene.add(this.ambientLight);
    }

    update(camera) {
        const camSector = worldToSector(camera.position.x, camera.position.y, camera.position.z);
        const needed = new Set();

        for (let dx = -RENDER_DISTANCE; dx <= RENDER_DISTANCE; dx++) {
            for (let dy = -RENDER_DISTANCE; dy <= RENDER_DISTANCE; dy++) {
                for (let dz = -RENDER_DISTANCE; dz <= RENDER_DISTANCE; dz++) {
                    const key = getSectorKey(camSector.sx + dx, camSector.sy + dy, camSector.sz + dz);
                    needed.add(key);
                }
            }
        }

        for (const [key, data] of this.activeSectors) {
            if (!needed.has(key)) {
                this.unloadSector(key, data);
            }
        }

        for (const key of needed) {
            if (!this.activeSectors.has(key)) {
                const [sx, sy, sz] = key.split(',').map(Number);
                this.loadSector(sx, sy, sz);
            }
        }
    }

    loadSector(sx, sy, sz) {
        const key = getSectorKey(sx, sy, sz);
        const sector = generateSector(sx, sy, sz);
        const group = new THREE.Group();
        group.name = `sector-${key}`;

        const starPoints = createStarPoints(sector);
        group.add(starPoints);

        for (const star of sector.stars) {
            if (star.hasPlanets) {
                const lightGroup = createStarLight(star);
                group.add(lightGroup);
            }
        }

        for (const planet of sector.planets) {
            const mesh = createPlanetMesh(planet);
            group.add(mesh);
        }

        this.scene.add(group);
        this.activeSectors.set(key, { sector, group });
    }

    unloadSector(key, data) {
        this.scene.remove(data.group);
        data.group.traverse((child) => {
            if (child.geometry) child.geometry.dispose();
            if (child.material) {
                if (Array.isArray(child.material)) {
                    child.material.forEach((m) => m.dispose());
                } else {
                    child.material.dispose();
                }
            }
        });
        this.activeSectors.delete(key);
    }

    getActiveSectorData() {
        return this.activeSectors;
    }
}
