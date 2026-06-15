document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-like').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var postId = this.dataset.postId;
            var self   = this;

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

});
