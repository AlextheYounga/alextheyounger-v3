import * as THREE from 'three';

export class FreeFlyControls {
    constructor(camera, domElement) {
        this.camera = camera;
        this.domElement = domElement;
        this.enabled = true;

        this.moveSpeed = 100;
        this.lookSpeed = 0.002;
        this.boostMultiplier = 5;

        this.euler = new THREE.Euler(0, 0, 0, 'YXZ');
        this.velocity = new THREE.Vector3();

        this.keys = {
            forward: false,
            backward: false,
            left: false,
            right: false,
            up: false,
            down: false,
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
            case 'KeyW': this.keys.forward = true; break;
            case 'KeyS': this.keys.backward = true; break;
            case 'KeyA': this.keys.left = true; break;
            case 'KeyD': this.keys.right = true; break;
            case 'Space': this.keys.up = true; break;
            case 'ShiftLeft': case 'ShiftRight': this.keys.down = true; break;
            case 'ControlLeft': case 'ControlRight': this.keys.boost = true; break;
        }
    }

    _onKeyUp(event) {
        switch (event.code) {
            case 'KeyW': this.keys.forward = false; break;
            case 'KeyS': this.keys.backward = false; break;
            case 'KeyA': this.keys.left = false; break;
            case 'KeyD': this.keys.right = false; break;
            case 'Space': this.keys.up = false; break;
            case 'ShiftLeft': case 'ShiftRight': this.keys.down = false; break;
            case 'ControlLeft': case 'ControlRight': this.keys.boost = false; break;
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

        const speed = this.moveSpeed * (this.keys.boost ? this.boostMultiplier : 1) * delta;

        const forward = new THREE.Vector3();
        this.camera.getWorldDirection(forward);

        const right = new THREE.Vector3();
        right.crossVectors(forward, this.camera.up).normalize();

        if (this.keys.forward) this.camera.position.addScaledVector(forward, speed);
        if (this.keys.backward) this.camera.position.addScaledVector(forward, -speed);
        if (this.keys.right) this.camera.position.addScaledVector(right, speed);
        if (this.keys.left) this.camera.position.addScaledVector(right, -speed);
        if (this.keys.up) this.camera.position.y += speed;
        if (this.keys.down) this.camera.position.y -= speed;
    }

    dispose() {
        document.removeEventListener('keydown', this._onKeyDown);
        document.removeEventListener('keyup', this._onKeyUp);
        document.removeEventListener('mousemove', this._onMouseMove);
        document.removeEventListener('pointerlockchange', this._onPointerLockChange);
        this.domElement.removeEventListener('click', this._onClick);
    }
}
