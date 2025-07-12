<!DOCTYPE html>
<html>
<head><title>DOM XSS</title></head>
<body>
    <h2>DOM XSS 테스트</h2>
    <input id="input" placeholder="메시지를 입력하세요">
    <button onclick="run()">실행</button>
    <div id="output"></div>
    <script>
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        window.onload = function () {
            var msg = getQueryParam('msg') || "default";
            document.getElementById('input').value = msg;
            document.getElementById('output').innerHTML = msg;
        };

        function run() {
            var msg = document.getElementById("input").value;
            var url = new URL(window.location.href);
            url.searchParams.set('msg', msg);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
