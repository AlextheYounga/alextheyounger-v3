import { mulberry32, seedFromSector } from './seededRandom.js';

const SECTOR_SIZE = 2000;
const MIN_STARS = 80;
const MAX_STARS = 300;
const PLANET_CHANCE = 0.15;
const MAX_PLANETS_PER_STAR = 4;

const STAR_COLORS = [
    { hue: 0, saturation: 0.8, lightness: 0.7 },
    { hue: 0.08, saturation: 0.6, lightness: 0.8 },
    { hue: 0.15, saturation: 0.4, lightness: 0.9 },
    { hue: 0.6, saturation: 0.8, lightness: 0.7 },
    { hue: 0.55, saturation: 0.5, lightness: 0.85 },
];

const PLANET_COLORS = [
    0xed4c4c, 0x146314, 0x0019ba, 0xf5f59d, 0xb55104,
    0x008787, 0xf5ac27, 0x800080, 0x8d9aa1, 0x595b5c,
    0xcc5500, 0x2e8b57, 0x4169e1, 0xdaa520,
];

export function getSectorSize() {
    return SECTOR_SIZE;
}

export function getSectorOrigin(sx, sy, sz) {
    return {
        x: sx * SECTOR_SIZE,
        y: sy * SECTOR_SIZE,
        z: sz * SECTOR_SIZE,
    };
}

export function getSectorKey(sx, sy, sz) {
    return `${sx},${sy},${sz}`;
}

export function worldToSector(wx, wy, wz) {
    return {
        sx: Math.floor(wx / SECTOR_SIZE),
        sy: Math.floor(wy / SECTOR_SIZE),
        sz: Math.floor(wz / SECTOR_SIZE),
    };
}

export function generateSector(sx, sy, sz) {
    const seed = seedFromSector(sx, sy, sz);
    const rng = mulberry32(seed);
    const origin = getSectorOrigin(sx, sy, sz);

    const starCount = Math.floor(rng() * (MAX_STARS - MIN_STARS)) + MIN_STARS;
    const stars = [];
    const planets = [];

    for (let i = 0; i < starCount; i++) {
        const x = origin.x + rng() * SECTOR_SIZE;
        const y = origin.y + rng() * SECTOR_SIZE;
        const z = origin.z + rng() * SECTOR_SIZE;

        const colorDef = STAR_COLORS[Math.floor(rng() * STAR_COLORS.length)];
        const saturation = colorDef.saturation - 0.1 + rng() * 0.2;
        const lightness = colorDef.lightness - 0.1 + rng() * 0.2;
        const size = rng() < 0.85 ? 3 + rng() * 4 : rng() < 0.95 ? 8 + rng() * 6 : 16 + rng() * 10;

        stars.push({
            index: i,
            x, y, z,
            hue: colorDef.hue,
            saturation,
            lightness,
            size,
            hasPlanets: rng() < PLANET_CHANCE,
        });

        if (stars[stars.length - 1].hasPlanets) {
            const planetCount = Math.floor(rng() * MAX_PLANETS_PER_STAR) + 1;
            for (let p = 0; p < planetCount; p++) {
                const orbitDistance = 15 + rng() * 80;
                const angle = rng() * Math.PI * 2;
                const inclination = (rng() - 0.5) * 0.3;

                const px = x + Math.cos(angle) * orbitDistance;
                const py = y + Math.sin(inclination) * orbitDistance * 0.3;
                const pz = z + Math.sin(angle) * orbitDistance;

                const color = PLANET_COLORS[Math.floor(rng() * PLANET_COLORS.length)];
                const radius = 0.3 + rng() * 3;

                planets.push({
                    index: planets.length,
                    parentStarIndex: i,
                    x: px, y: py, z: pz,
                    color,
                    radius,
                    orbitDistance,
                    orbitAngle: angle,
                });
            }
        }
    }

    return {
        key: getSectorKey(sx, sy, sz),
        sx, sy, sz,
        stars,
        planets,
    };
}
