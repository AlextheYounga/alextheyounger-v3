import * as THREE from 'three';
import { generateSector, getSectorKey, worldToSector } from './sectorGenerator.js';
import { createStarPoints, createPlanetMesh, createStarLight } from './renderer.js';

const RENDER_DISTANCE = 1;

function getNeededSectors(camSector, renderDistance) {
    const offsets = Array.from({ length: renderDistance * 2 + 1 }, (_, index) => index - renderDistance);

    return offsets.flatMap((dx) =>
        offsets.flatMap((dy) =>
            offsets.map((dz) => ({
                sx: camSector.sx + dx,
                sy: camSector.sy + dy,
                sz: camSector.sz + dz,
            })),
        ),
    );
}

export class SectorManager {
    constructor(scene) {
        this.scene = scene;
        this.activeSectors = new Map();
        this.ambientLight = new THREE.AmbientLight(0x111111);
        scene.add(this.ambientLight);
    }

    update(camera) {
        const camSector = worldToSector(camera.position.x, camera.position.y, camera.position.z);
        const needed = getNeededSectors(camSector, RENDER_DISTANCE);
        const neededKeys = new Set(needed.map(({ sx, sy, sz }) => getSectorKey(sx, sy, sz)));

        for (const [key, data] of this.activeSectors) {
            if (!neededKeys.has(key)) {
                this.unloadSector(key, data);
            }
        }

        for (const { sx, sy, sz } of needed) {
            const key = getSectorKey(sx, sy, sz);

            if (!this.activeSectors.has(key)) {
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

    getNearbyGravityBodies(position, radius = 5000, maxBodies = 24) {
        const nearby = [];
        const radiusSq = radius * radius;

        for (const { sector } of this.activeSectors.values()) {
            for (const star of sector.stars) {
                const dx = star.x - position.x;
                const dy = star.y - position.y;
                const dz = star.z - position.z;
                const distSq = dx * dx + dy * dy + dz * dz;

                if (distSq <= radiusSq) {
                    const starRadius = Math.max(8, star.size * 2.5);
                    nearby.push({
                        type: 'star',
                        x: star.x,
                        y: star.y,
                        z: star.z,
                        mass: 18000 + (star.size * star.size * 140),
                        safeRadius: starRadius * 1.8,
                        distanceSq: distSq,
                    });
                }
            }

            for (const planet of sector.planets) {
                const dx = planet.x - position.x;
                const dy = planet.y - position.y;
                const dz = planet.z - position.z;
                const distSq = dx * dx + dy * dy + dz * dz;

                if (distSq <= radiusSq) {
                    const safeRadius = Math.max(4, planet.radius * 3.5);
                    nearby.push({
                        type: 'planet',
                        x: planet.x,
                        y: planet.y,
                        z: planet.z,
                        mass: 250 + (planet.radius * planet.radius * 120),
                        safeRadius,
                        distanceSq: distSq,
                    });
                }
            }
        }

        nearby.sort((a, b) => a.distanceSq - b.distanceSq);
        return nearby.slice(0, maxBodies);
    }
}
