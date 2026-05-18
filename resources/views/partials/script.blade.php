<script>
    let ytPlayers = {};
    let lastTime = {};
    // ইউজারের লগইন স্ট্যাটাস এবং রোল পাস করা হয়েছে
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    const userRole = "{{ Auth::check() ? Auth::user()->role : 'guest' }}";

    // --- FACEBOOK SDK INITIALIZATION ---
    window.fbAsyncInit = function() {
        FB.init({
            xfbml: true,
            version: 'v18.0'
        });
    };

    // --- FACEBOOK PLAY LOGIC ---
    function playFBVideo(id) {
        const starter = document.getElementById('fb-starter-' + id);
        const timerStatus = document.getElementById('fb-timer-status-' + id);
        const overlay = document.getElementById('fb-overlay-' + id);

        starter.style.display = 'none';
        timerStatus.classList.remove('hidden');
        overlay.classList.remove('hidden');

        let timeLeft = {{ $video->duration ?? 30 }};
        timerStatus.innerHTML = `⏳ <span id="timer-count-${id}">${timeLeft}</span>s remaining`;

        FB.XFBML.parse(document.getElementById('fb-container-' + id), function() {
            FB.Event.subscribe('xfbml.ready', function(msg) {
                if (msg.type === 'video' && msg.id === 'fb-player-' + id) {
                    let myPlayer = msg.instance;
                    myPlayer.unmute();
                    myPlayer.play();
                    myPlayer.setVolume(1);
                }
            });
        });

        let countdown = setInterval(() => {
            timeLeft--;
            let currentTimerSpan = document.getElementById('timer-count-' + id);
            if (currentTimerSpan) currentTimerSpan.innerText = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                overlay.classList.add('hidden');
                timerStatus.innerHTML = "✅ Task Finished!";
                timerStatus.className =
                    "absolute top-4 left-4 z-40 bg-green-600 text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase";

                // এখানে ফিনিশ টাস্ক কল হবে
                finishTask(id);
            }
        }, 1000);
    }

    // --- YOUTUBE LOGIC ---
    function onYouTubeIframeAPIReady() {
        document.querySelectorAll('.yt-wrapper').forEach(w => {
            let tid = w.dataset.taskId;
            ytPlayers[tid] = new YT.Player('player-' + tid, {
                videoId: w.dataset.videoId,
                playerVars: {
                    'controls': 0,
                    'disablekb': 1,
                    'rel': 0,
                    'modestbranding': 1
                },
                events: {
                    'onStateChange': (e) => {
                        let ui = document.getElementById('yt-ui-' + tid);
                        if (e.data === 1) {
                            ui.style.opacity = '0';
                            startSecurityYT(tid);
                        } else {
                            ui.style.opacity = '1';
                        }
                        if (e.data === 0) finishTask(tid);
                    }
                }
            });
        });
    }

    function toggleYT(id) {
        let state = ytPlayers[id].getPlayerState();
        state === 1 ? ytPlayers[id].pauseVideo() : ytPlayers[id].playVideo();
    }

    function startSecurityYT(id) {
        setInterval(() => {
            if (ytPlayers[id] && ytPlayers[id].getPlayerState() === 1) {
                let curr = ytPlayers[id].getCurrentTime();
                if (curr - (lastTime[id] || 0) > 2.0) ytPlayers[id].seekTo(lastTime[id] || 0);
                else lastTime[id] = curr;
            }
        }, 500);
    }

    // --- LOCAL LOGIC ---
    function toggleLocal(id) {
        let v = document.getElementById('local-' + id);
        let ui = document.getElementById('local-ui-' + id);
        if (v.paused) {
            v.play();
            ui.style.opacity = '0';
            v.onended = () => finishTask(id);
        } else {
            v.pause();
            ui.style.opacity = '1';
        }
    }

    // --- API CALL & ROLE CHECK ---
    function finishTask(taskId) {
        // ১. লগইন চেক
        if (!isLoggedIn) {
            showFinalMessage(taskId, false, "টাকা আয় করতে হলে আপনাকে আগে লগইন করতে হবে।");
            return;
        }

        // ২. n_user চেক (যাতে তাদের টাকা অ্যাড না হয়)
        if (userRole === 'n_user') {
            showFinalMessage(taskId, false, "টাকা আয় করতে আপনার একাউন্টটি একটি প্যাকেজ দিয়ে একটিভ করুন।");
            return;
        }

        // ৩. সার্ভারে রিকোয়েস্ট (শুধুমাত্র প্রিমিয়াম ইউজারদের জন্য)
        fetch("{{ route('complete.task') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    video_id: taskId
                })
            })
            .then(r => r.json())
            .then(data => {
                showFinalMessage(taskId, data.success, data.message);
            })
            .catch(() => showFinalMessage(taskId, false, "সার্ভার এরর! আবার চেষ্টা করুন।"));
    }

    // মেসেজ দেখানোর ফাংশন (ডুপ্লিকেট রিমুভ করা হয়েছে)
    function showFinalMessage(id, success, msg) {
        const overlay = document.getElementById('msg-overlay-' + id);
        const statusIcon = document.getElementById('status-icon-' + id);
        const statusTitle = document.getElementById('status-title-' + id);
        const statusText = document.getElementById('status-text-' + id);
        const actionBtn = overlay.querySelector('button');

        statusIcon.innerText = success ? "✅" : "❌";
        statusTitle.innerText = success ? "Success!" : "Failed!";
        statusText.innerText = msg;

        if (userRole === 'n_user' || !isLoggedIn) {
            actionBtn.innerText = "Upgrade Account";
            actionBtn.onclick = () => window.location.href = "/upgrade";
        } else {
            actionBtn.innerText = "Collect & Next";
            actionBtn.onclick = () => location.reload();
        }

        overlay.classList.remove('hidden');
    }
</script>
