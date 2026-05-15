import * as THREE from 'three';

export class FreeFlyControls {
    constructor(camera, domElement) {
        this.camera = camera;
        this.domElement = domElement;
        this.enabled = true;

        this.moveSpeed = 20;
        this.lookSpeed = 0.002;
        this.boostMultiplier = 5;
        this.maxSpeed = 500;
        this.gravityInfluenceRadius = 5000;
        this.gravitySoftening = 120;
        this.gravityScale = 0.07;
        this.collisionBounce = 0.1;

        this.euler = new THREE.Euler(0, 0, 0, 'YXZ');
        this.velocity = new THREE.Vector3();

        this.keys = {
            boost: false,
        };

        this._onKeyDown = this._onKeyDown.bind(this);
        this._onKeyUp = this._onKeyUp.bind(this);
        this._onMouseMove = this._onMouseMove.bind(this);
        this._onPointerLockChange = this._onPointerLockChange.bind(this);
        this._onClick = this._onClick.bind(this);

        document.addEventListener('keydown', this._onKeyDown);
        document.addEventListener('keyup', this._onKeyUp);
        document.addEventListener('mousemove', this._onMouseMove);
        document.addEventListener('pointerlockchange', this._onPointerLockChange);
        domElement.addEventListener('click', this._onClick);

        this._extractCameraRotation();
    }

    _extractCameraRotation() {
        const direction = new THREE.Vector3();
        this.camera.getWorldDirection(direction);
        this.euler.setFromQuaternion(this.camera.quaternion, 'YXZ');
    }

    _onClick() {
        this.domElement.requestPointerLock();
    }

    _onPointerLockChange() {
        this.enabled = document.pointerLockElement === this.domElement;
    }

    _onKeyDown(event) {
        switch (event.code) {
            case 'Space': this.keys.boost = true; break;
        }
    }

    _onKeyUp(event) {
        switch (event.code) {
            case 'Space': this.keys.boost = false; break;
        }
    }

    _onMouseMove(event) {
        if (!this.enabled) return;

        this.euler.y -= event.movementX * this.lookSpeed;
        this.euler.x -= event.movementY * this.lookSpeed;
        this.euler.x = Math.max(-Math.PI / 2, Math.min(Math.PI / 2, this.euler.x));

        this.camera.quaternion.setFromEuler(this.euler);
    }

    update(delta) {
        if (!this.enabled) return;

        this.updateWithPhysics(delta, []);
    }

    updateWithPhysics(delta, gravityBodies = []) {
        if (!this.enabled) return;

        const clampedDelta = Math.min(delta, 0.05);
        const thrust = this.keys.boost ? this.moveSpeed * this.boostMultiplier : 0;

        const forward = new THREE.Vector3();
        this.camera.getWorldDirection(forward);

        const acceleration = new THREE.Vector3();

        if (thrust > 0) {
            acceleration.addScaledVector(forward, thrust);
        }

        for (const body of gravityBodies) {
            const bodyPosition = new THREE.Vector3(body.x, body.y, body.z);
            const toBody = bodyPosition.sub(this.camera.position);
            const distanceSq = Math.max(toBody.lengthSq(), 1);
            const direction = toBody.normalize();
            const gravity = (body.mass * this.gravityScale) / (distanceSq + this.gravitySoftening);

            acceleration.addScaledVector(direction, gravity);
        }

        this.velocity.addScaledVector(acceleration, clampedDelta);

        const speed = this.velocity.length();
        if (speed > this.maxSpeed) {
            this.velocity.multiplyScalar(this.maxSpeed / speed);
        }

        this.camera.position.addScaledVector(this.velocity, clampedDelta);

        this._resolveBodySafety(gravityBodies);
    }

    _resolveBodySafety(gravityBodies) {
        for (const body of gravityBodies) {
            const bodyPosition = new THREE.Vector3(body.x, body.y, body.z);
            const toCamera = this.camera.position.clone().sub(bodyPosition);
            const distance = toCamera.length();
            const minSafeDistance = body.safeRadius;

            if (distance >= minSafeDistance || distance === 0) {
                continue;
            }

            const outward = toCamera.normalize();
            this.camera.position.copy(bodyPosition).addScaledVector(outward, minSafeDistance + 1);

            const radialVelocity = this.velocity.dot(outward);
            if (radialVelocity < 0) {
                this.velocity.addScaledVector(outward, -(1 + this.collisionBounce) * radialVelocity);
            } else {
                this.velocity.addScaledVector(outward, 8);
            }
        }
    }

    dispose() {
        document.removeEventListener('keydown', this._onKeyDown);
        document.removeEventListener('keyup', this._onKeyUp);
        document.removeEventListener('mousemove', this._onMouseMove);
        document.removeEventListener('pointerlockchange', this._onPointerLockChange);
        this.domElement.removeEventListener('click', this._onClick);
    }
}
