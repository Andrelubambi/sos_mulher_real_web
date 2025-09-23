 
export function setupVideoCall() {
    const videoCallBtn = document.getElementById('videoCallBtn');
    const videoModal = document.getElementById('videoModal');
    const closeVideoBtn = document.getElementById('closeVideoBtn');
    const endCallBtn = document.getElementById('endCallBtn');
    const toggleCameraBtn = document.getElementById('toggleCameraBtn');
    const toggleMicBtn = document.getElementById('toggleMicBtn');
    const jitsiFrame = document.getElementById('jitsiFrame');
    const videoCallTitle = document.getElementById('videoCallTitle');

    let isCameraOn = true;
    let isMicOn = true;

    videoCallBtn.addEventListener('click', () => {
        if (!window.usuarioAtualId) return;
        const roomName = `chat-${Math.min(parseInt(window.usuarioLogadoId), parseInt(window.usuarioAtualId))}-${Math.max(parseInt(window.usuarioLogadoId), parseInt(window.usuarioAtualId))}`;
        jitsiFrame.src = `https://meet.jit.si/${roomName}`;
        videoCallTitle.textContent = `Videochamada com ${document.getElementById('participantName').textContent}`;
        videoModal.classList.add('active');
        window.isCallActive = true;
    });

    closeVideoBtn.addEventListener('click', () => {
        endVideoCall();
    });

    endCallBtn.addEventListener('click', () => {
        endVideoCall();
    });

    toggleCameraBtn.addEventListener('click', () => {
        isCameraOn = !isCameraOn;
        jitsiFrame.contentWindow.postMessage({
            type: 'toggle-camera',
            enabled: isCameraOn
        }, '*');
        toggleCameraBtn.querySelector('i').className = isCameraOn ? 'fas fa-video' : 'fas fa-video-slash';
    });

    toggleMicBtn.addEventListener('click', () => {
        isMicOn = !isMicOn;
        jitsiFrame.contentWindow.postMessage({
            type: 'toggle-microphone',
            enabled: isMicOn
        }, '*');
        toggleMicBtn.querySelector('i').className = isMicOn ? 'fas fa-microphone' : 'fas fa-microphone-slash';
    });

    window.endVideoCall = () => {
        jitsiFrame.src = '';
        videoModal.classList.remove('active');
        window.isCallActive = false;
        isCameraOn = true;
        isMicOn = true;
        toggleCameraBtn.querySelector('i').className = 'fas fa-video';
        toggleMicBtn.querySelector('i').className = 'fas fa-microphone';
    };
}
