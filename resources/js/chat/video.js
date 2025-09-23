export function setupVideoCall() {
    const videoCallBtn = document.getElementById('videoCallBtn');
    const videoModal = document.getElementById('videoModal');
    const closeVideoBtn = document.getElementById('closeVideoBtn');
    const jitsiFrame = document.getElementById('jitsiFrame');
    const videoCallTitle = document.getElementById('videoCallTitle');
    const chatHeader = document.getElementById('chatHeader');

    window.isCallActive = false;
    window.currentRoomUrl = null;

    videoCallBtn.addEventListener('click', function() {
        if (!window.usuarioAtualId || window.usuarioAtualId == document.querySelector('meta[name="user-id"]').getAttribute('content')) {
            alert('Selecione um usuário para iniciar a videochamada');
            return;
        }
        
        videoCallBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        videoCallBtn.disabled = true;
        
        fetch(`/video-call/room/${window.usuarioAtualId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.room_url) {
                window.currentRoomUrl = data.room_url;
                startVideoCall(data.room_url, data.room_id);
            } else {
                throw new Error(data.error || 'Erro desconhecido ao iniciar videochamada');
            }
        })
        .catch(error => {
            console.error('Erro ao iniciar videochamada:', error);
            alert('Erro ao iniciar videochamada: ' + error.message);
        })
        .finally(() => {
            videoCallBtn.innerHTML = '<i class="fas fa-video"></i>';
            updateVideoCallButton();
        });
    });

    function startVideoCall(roomUrl, roomId) {
        videoCallTitle.textContent = `Videochamada - ${chatHeader.textContent}`;
        jitsiFrame.src = roomUrl;
        videoModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        window.isCallActive = true;
        videoCallBtn.innerHTML = '<i class="fas fa-phone"></i>';
        videoCallBtn.title = 'Chamada ativa';
        videoCallBtn.classList.add('active');
    }

    window.endVideoCall = function() {
        videoModal.classList.remove('show');
        document.body.style.overflow = 'auto';
        jitsiFrame.src = 'about:blank';
        window.isCallActive = false;
        window.currentRoomUrl = null;
        videoCallBtn.classList.remove('active');
        updateVideoCallButton();
    };

    closeVideoBtn.addEventListener('click', window.endVideoCall);

    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            window.endVideoCall();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal.classList.contains('show')) {
            window.endVideoCall();
        }
    });

    function updateVideoCallButton() {
        if (window.usuarioAtualId && window.usuarioAtualId != document.querySelector('meta[name="user-id"]').getAttribute('content')) {
            videoCallBtn.disabled = false;
            videoCallBtn.style.opacity = '1';
            videoCallBtn.title = 'Iniciar videochamada';
        } else {
            videoCallBtn.disabled = true;
            videoCallBtn.style.opacity = '0.5';
            videoCallBtn.title = 'Selecione um usuário para iniciar videochamada';
        }
    }
}