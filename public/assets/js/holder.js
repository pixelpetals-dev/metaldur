import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js';

const container = document.getElementById('tool-canvas');

let scene, camera, renderer, toolGroup;
let isDragging = false;
let previousMousePosition = { x: 0 };
let rotationVelocity = 0;

const IDLE_SPEED = 0.01; 

const DRAG_SENSITIVITY = 0.005;
const FRICTION = 0.96;

const materials = {
    shankSteel: new THREE.MeshStandardMaterial({ 
        color: 0x888888, 

        metalness: 0.8, 
        roughness: 0.2,
    }),

    coatingCopper: new THREE.MeshStandardMaterial({ 
        color: 0xb87333, 

        metalness: 0.7, 
        roughness: 0.3,
        emissive: 0x552200,
        emissiveIntensity: 0.1
    })
};

init();
animate();

function init() {

    scene = new THREE.Scene();

    const aspect = container.clientWidth / container.clientHeight;
    camera = new THREE.PerspectiveCamera(35, aspect, 0.1, 100);
    camera.position.set(0, 0, 14); 
    camera.lookAt(0, 0, 0);

    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    container.appendChild(renderer.domElement);

    scene.add(new THREE.AmbientLight(0xffffff, 0.4));

    const mainLight = new THREE.DirectionalLight(0xffffff, 2.5);
    mainLight.position.set(5, 5, 10);
    mainLight.castShadow = true;
    mainLight.shadow.mapSize.width = 1024;
    mainLight.shadow.mapSize.height = 1024;
    scene.add(mainLight);

    const rimLight = new THREE.SpotLight(0x3b82f6, 10);
    rimLight.position.set(-8, 0, -5);
    rimLight.lookAt(0, 0, 0);
    scene.add(rimLight);

    const fillLight = new THREE.PointLight(0xffaa00, 0.5);
    fillLight.position.set(5, -8, 5);
    scene.add(fillLight);

    createEndMill();

    window.addEventListener('resize', onResize);

    container.addEventListener('mousedown', onPointerDown);
    container.addEventListener('touchstart', onPointerDown, {passive: false});
    window.addEventListener('mousemove', onPointerMove);
    window.addEventListener('touchmove', onPointerMove, {passive: false});
    window.addEventListener('mouseup', onPointerUp);
    window.addEventListener('touchend', onPointerUp);
}

function createEndMill() {
    toolGroup = new THREE.Group();

    const shankRadius = 0.8;
    const shankHeight = 5;
    const fluteHeight = 6;
    const totalHeight = shankHeight + fluteHeight;

    const textTex = createLabelTexture("D10*100*4T");
    const shankMatWithText = materials.shankSteel.clone();
    shankMatWithText.map = textTex;

    const shankGeo = new THREE.CylinderGeometry(shankRadius, shankRadius, shankHeight, 32);
    const shank = new THREE.Mesh(shankGeo, shankMatWithText);
    shank.position.y = fluteHeight / 2; 

    shank.castShadow = true;
    shank.receiveShadow = true;

    shank.rotation.y = -Math.PI / 2;

    toolGroup.add(shank);

    const shape = new THREE.Shape();
    const r = shankRadius; 

    const inner = shankRadius * 0.6; 

    const steps = 4; 

    for (let i = 0; i < steps * 2; i++) {
        const angle = (i / (steps * 2)) * Math.PI * 2;
        const radius = i % 2 === 0 ? r : inner;
        const x = Math.cos(angle) * radius;
        const y = Math.sin(angle) * radius;
        if (i === 0) shape.moveTo(x, y);
        else shape.lineTo(x, y);
    }
    shape.closePath();

    const extrudeSettings = {
        depth: fluteHeight,
        bevelEnabled: false,
        steps: 60, 

        curveSegments: 12
    };

    const fluteGeo = new THREE.ExtrudeGeometry(shape, extrudeSettings);

    fluteGeo.center();

    const posAttribute = fluteGeo.attributes.position;
    const vertex = new THREE.Vector3();
    const twistFactor = 1.5; 

    for ( let i = 0; i < posAttribute.count; i ++ ) {
        vertex.fromBufferAttribute( posAttribute, i );

        const yPercent = (vertex.z + (fluteHeight/2)) / fluteHeight; 

        const angle = yPercent * Math.PI * 2 * twistFactor;

        const x = vertex.x * Math.cos(angle) - vertex.y * Math.sin(angle);
        const y = vertex.x * Math.sin(angle) + vertex.y * Math.cos(angle);

        posAttribute.setXY( i, x, y );
    }

    fluteGeo.computeVertexNormals();

    const flutes = new THREE.Mesh(fluteGeo, materials.coatingCopper);

    flutes.rotation.x = Math.PI / 2; 
    flutes.position.y = -shankHeight / 2; 

    flutes.castShadow = true;

    toolGroup.add(flutes);

    toolGroup.position.y = 0.5;

    toolGroup.rotation.z = 0.2; 
    toolGroup.rotation.x = 0.1;

    scene.add(toolGroup);
}

function createLabelTexture(text) {
    const canvas = document.createElement('canvas');
    canvas.width = 512;
    canvas.height = 256;
    const ctx = canvas.getContext('2d');

    ctx.fillStyle = '#888888'; 

    ctx.fillRect(0,0,512,256);

    ctx.fillStyle = '#333333'; 

    ctx.font = 'bold 60px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    ctx.fillText(text, 256, 128);

    const tex = new THREE.CanvasTexture(canvas);
    return tex;
}

function onPointerDown(e) {
    isDragging = true;
    const x = e.touches ? e.touches[0].clientX : e.clientX;
    previousMousePosition = { x: x };
    container.style.cursor = 'grabbing';
    rotationVelocity = 0; 

}

function onPointerMove(e) {
    if (!isDragging) return;
    const x = e.touches ? e.touches[0].clientX : e.clientX;
    const delta = x - previousMousePosition.x;

    rotationVelocity = delta * DRAG_SENSITIVITY;

    previousMousePosition = { x: x };
}

function onPointerUp() {
    isDragging = false;
    container.style.cursor = 'grab';
}

function onResize() {
    if(!container) return;
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
}

function animate() {
    requestAnimationFrame(animate);

    if (isDragging) {

        toolGroup.rotation.y += rotationVelocity;
    } else {

        if (Math.abs(rotationVelocity) > IDLE_SPEED) {

            rotationVelocity *= FRICTION;
        } else {

            rotationVelocity = IDLE_SPEED;
        }

        toolGroup.rotation.y += rotationVelocity;
    }

    toolGroup.position.y = 0.5 + Math.sin(Date.now() * 0.001) * 0.05;

    renderer.render(scene, camera);
}