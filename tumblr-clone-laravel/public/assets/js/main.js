document.addEventListener('DOMContentLoaded', function () {

    function glitchFlash() {
        var flash = document.createElement('div');
        flash.style.cssText = 'position:fixed;inset:0;z-index:99999;pointer-events:none;mix-blend-mode:difference;animation:glitchFlash 0.15s ease-out;';
        flash.style.background = '#c93636';
        flash.style.opacity = '0.15';
        document.body.appendChild(flash);
        setTimeout(function () { flash.remove(); }, 150);
    }

    document.querySelectorAll('.btn-like').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var postId = this.dataset.postId;
            var self   = this;

            self.style.transform = 'scale(1.3)';
            setTimeout(function () { self.style.transform = ''; }, 150);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', BASE_URL + '/like', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        var res = JSON.parse(xhr.responseText);
                        self.classList.toggle('liked', res.liked);
                        self.querySelector('.like-count').textContent = res.count;
                        if (res.liked) { glitchFlash(); }
                    } catch (err) {
                        console.error('Invalid JSON response');
                    }
                }
            };

            xhr.send('post_id=' + encodeURIComponent(postId));
        });
    });

    document.querySelectorAll('.btn-follow').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var userId = this.dataset.userId;
            var self   = this;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', BASE_URL + '/follow', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        var res = JSON.parse(xhr.responseText);
                        self.classList.toggle('following', res.following);
                        self.textContent = res.following ? 'Urmărești' : 'Urmărește';
                    } catch (err) {
                        console.error('Invalid JSON response');
                    }
                }
            };

            xhr.send('user_id=' + encodeURIComponent(userId));
        });
    });

    /* Color picker: highlight selected */
    document.querySelectorAll('.color-option input').forEach(function (input) {
        input.addEventListener('change', function () {
            document.querySelectorAll('.color-option span').forEach(function (s) {
                s.style.borderColor = 'transparent';
            });
            if (this.checked) {
                this.nextElementSibling.style.borderColor = '#fff';
            }
        });
    });

    /* Ghostwriter AI: generate post content */
    var aiBtn = document.getElementById('btn-ai-generate');
    if (aiBtn) {
        aiBtn.addEventListener('click', function () {
            var idea = document.getElementById('ai-idea').value.trim();
            if (!idea) return;

            var type = document.getElementById('type').value;
            var loading = document.getElementById('ai-loading');
            var titleInput = document.getElementById('title');
            var bodyInput = document.getElementById('body');

            loading.style.display = 'inline';
            aiBtn.disabled = true;

            fetch(BASE_URL + '/ai/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ idea: idea, type: type }),
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.title) titleInput.value = data.title;
                if (data.body) bodyInput.value = data.body;
            })
            .catch(function () {
                alert('Eroare la generarea conținutului AI.');
            })
            .finally(function () {
                loading.style.display = 'none';
                aiBtn.disabled = false;
            });
        });
    }

    /* Notification badge polling */
    var badge = document.getElementById('notif-badge');
    if (badge) {
        function updateBadge() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', BASE_URL + '/notifications/count', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        var res = JSON.parse(xhr.responseText);
                        if (res.count > 0) {
                            badge.textContent = res.count;
                            badge.style.display = 'inline-flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    } catch (err) {}
                }
            };
            xhr.send();
        }
        updateBadge();
        setInterval(updateBadge, 15000);
    }

});
