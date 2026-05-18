  <script>
      let ytPlayers = {};
      let lastTime = {};
      const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

      // --- FACEBOOK SDK INITIALIZATION ---
      window.fbAsyncInit = function() {
          FB.init({
              xfbml: true,
              version: 'v18.0'
          });
      };

      // --- FACEBOOK PLAY LOGIC ---
      // --- FACEBOOK PLAY LOGIC ---
      function playFBVideo(id) {


          const starter = document.getElementById('fb-starter-' + id);
          const timerStatus = document.getElementById('fb-timer-status-' + id);
          const overlay = document.getElementById('fb-overlay-' + id);

          // ২. UI পরিবর্তন
          starter.style.display = 'none';
          timerStatus.classList.remove('hidden');
          overlay.classList.remove('hidden');

          // টাইমার শুরুর আগেই টেক্সট সেট করুন
          let timeLeft = {{ $video->duration ?? 30 }}; // সেকেন্ডে (যেমন ৩০ সেকেন্ড)
          timerStatus.innerHTML = `⏳ <span id="timer-count-${id}">${timeLeft}</span>s remaining`;

          // ৩. Facebook Video Load and Play
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

          // ৪. কাউন্টডাউন টাইমার লজিক
          let countdown = setInterval(() => {
              timeLeft--;
              let currentTimerSpan = document.getElementById('timer-count-' + id);

              if (currentTimerSpan) {
                  currentTimerSpan.innerText = timeLeft;
              }

              if (timeLeft <= 0) {
                  clearInterval(countdown);
                  overlay.classList.add('hidden'); // ভিডিওতে ক্লিক করার সুযোগ দিন

                  // সাকসেস স্টাইল
                  timerStatus.innerHTML = "✅ Task Finished!";
                  timerStatus.className =
                      "absolute top-4 left-4 z-40 bg-green-600 text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase";

                  // ৫. ডাটাবেসে টাকা অ্যাড করার ফাংশন কল করুন
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

      // --- API CALL ---
      // --- API CALL & LOGIN CHECK ---
      function finishTask(taskId) {
          // ১. ভিডিও শেষ হওয়ার পর চেক করা হচ্ছে ইউজার লগইন করা কি না
          if (!isLoggedIn) {
              // লগইন না থাকলে সরাসরি এরর মেসেজ দেখাবে
              showFinalMessage(taskId, false, "টাকা আয় করতে হলে আপনাকে আগে লগইন করতে হবে।");
              return;
          }

          // ২. ইউজার লগইন করা থাকলে সার্ভারে রিকোয়েস্ট পাঠানো হবে টাকা অ্যাড করার জন্য
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
                  if (data.success) {
                      showFinalMessage(taskId, true, data.message);
                  } else {
                      showFinalMessage(taskId, false, data.message);
                  }
              })
              .catch(() => showFinalMessage(taskId, false, "সার্ভার এরর! আবার চেষ্টা করুন।"));
      }

      // মেসেজ দেখানোর ফাংশন (ডিজাইন একটু আপডেট করা হয়েছে)
      function showFinalMessage(id, success, msg) {
          const overlay = document.getElementById('msg-overlay-' + id);
          const statusIcon = document.getElementById('status-icon-' + id);
          const statusTitle = document.getElementById('status-title-' + id);
          const statusText = document.getElementById('status-text-' + id);
          const actionBtn = overlay.querySelector('button');

          statusIcon.innerText = success ? "✅" : "❌";
          statusTitle.innerText = success ? "অভিনন্দন!" : "ব্যর্থ হয়েছে!";
          statusText.innerText = msg;

          // যদি লগইন না থাকার কারণে ব্যর্থ হয়, তবে বাটনের টেক্সট 'Login Now' করে দেওয়া যেতে পারে
          if (!isLoggedIn && !success) {
              actionBtn.innerText = "Login Now";
              actionBtn.onclick = () => window.location.href = "{{ route('login') }}";
          } else {
              actionBtn.innerText = "Collect & Next";
              actionBtn.onclick = () => location.reload();
          }

          overlay.classList.remove('hidden');
      }

      function showFinalMessage(id, success, msg) {
          const overlay = document.getElementById('msg-overlay-' + id);
          document.getElementById('status-icon-' + id).innerText = success ? "✅" : "❌";
          document.getElementById('status-title-' + id).innerText = success ? "Success!" : "Failed!";
          document.getElementById('status-text-' + id).innerText = msg;
          overlay.classList.remove('hidden');
      }
  </script>
  {{-- end of video watch section --}}



  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
      // Update User Function
      async function saveUserChanges(userId) {
          const role = document.getElementById(`role-${userId}`).value;
          const type = document.getElementById(`type-${userId}`).value;
          const amount = document.getElementById(`amount-${userId}`).value;
          const btn = document.getElementById(`btn-${userId}`);
          const loader = document.getElementById(`loader-${userId}`);

          btn.disabled = true;
          loader.classList.remove('hidden');

          try {
              const response = await fetch(`/admin/users/update-inline/${userId}`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({
                      role,
                      type,
                      paid_amount: amount
                  })
              });

              const data = await response.json();
              if (data.success) {
                  Swal.fire({
                      toast: true,
                      position: 'top-end',
                      icon: 'success',
                      title: 'Updated!',
                      showConfirmButton: false,
                      timer: 1500,
                      background: '#161925',
                      color: '#fff'
                  });
                  btn.classList.replace('bg-yellow-500', 'bg-emerald-500');
                  btn.querySelector('span').innerText = 'Done';
                  setTimeout(() => {
                      btn.classList.replace('bg-emerald-500', 'bg-yellow-500');
                      btn.querySelector('span').innerText = 'Save';
                      btn.disabled = false;
                  }, 2000);
              }
          } catch (error) {
              Swal.fire('Error', 'Update failed', 'error');
              btn.disabled = false;
          } finally {
              loader.classList.add('hidden');
          }
      }

      // Delete User Function
      async function deleteUser(userId) {
          const result = await Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#eab308',
              cancelButtonColor: '#ef4444',
              confirmButtonText: 'Yes, delete it!',
              background: '#161925',
              color: '#fff'
          });

          if (result.isConfirmed) {
              try {
                  const response = await fetch(`/admin/users/delete/${userId}`, {
                      method: 'DELETE',
                      headers: {
                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                          'Accept': 'application/json'
                      }
                  });

                  const data = await response.json();

                  if (response.ok && data.success) {
                      const row = document.getElementById(`user-row-${userId}`);
                      row.style.opacity = '0';
                      row.style.transform = 'translateX(20px)';
                      setTimeout(() => row.remove(), 500);
                      Swal.fire('Deleted!', data.message, 'success');
                  } else {
                      Swal.fire('Error', data.message || 'Error occurred', 'error');
                  }
              } catch (error) {
                  Swal.fire('Error', 'Network or Server Error', 'error');
              }
          }
      }
  </script>

  {{-- end user list of admin --}}
