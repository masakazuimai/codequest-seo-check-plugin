(function() {
    var btn = document.getElementById('cqseo-verify-key');
    if (!btn) return;

    var data = window.cqseoSettingsData || {};
    var i18n = data.i18n || {};

    btn.addEventListener('click', function() {
        var key = document.getElementById('cqseo_api_key').value.trim();
        var result = document.getElementById('cqseo-verify-result');

        function showResult(color, symbol, message) {
            var span = document.createElement('span');
            span.style.color = color;
            span.textContent = symbol + ' ' + message;
            result.innerHTML = '';
            result.appendChild(span);
        }

        if (!key) {
            showResult('#b91c1c', '\u2717', i18n.enterKey || 'Please enter an API key');
            return;
        }

        btn.disabled = true;
        result.textContent = i18n.verifying || 'Verifying...';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', data.ajaxUrl || '');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            btn.disabled = false;
            try {
                var res = JSON.parse(xhr.responseText);
                if (res.success) {
                    showResult('#15803d', '\u2713', res.data.message);
                } else {
                    showResult('#b91c1c', '\u2717', (res.data && res.data.message ? res.data.message : (i18n.verifyFailed || 'Verification failed')));
                }
            } catch(e) {
                showResult('#b91c1c', '\u2717', i18n.verifyFailed || 'Verification failed');
            }
        };
        xhr.onerror = function() {
            btn.disabled = false;
            showResult('#b91c1c', '\u2717', i18n.networkError || 'Network error');
        };
        xhr.send('action=cqseo_verify_key&nonce=' + encodeURIComponent(data.nonce || '') + '&api_key=' + encodeURIComponent(key));
    });
})();
