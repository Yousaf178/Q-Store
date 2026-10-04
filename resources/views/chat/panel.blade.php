@php
    // Shared chat UI: used by the customer page and the admin conversation page.
    // $messages, $panelTitle, $feedUrl and $sendUrl are provided by the parent view.
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-chat-dots text-primary me-2"></i>{{ $panelTitle }}
        </h5>
        <span class="small text-muted">
            <i class="bi bi-arrow-repeat me-1"></i>Updates automatically
        </span>
    </div>

    <div class="card-body bg-light" id="chatThread" style="height: 26rem; overflow-y: auto;">
        @include('chat.messages', ['messages' => $messages])
    </div>

    <div class="card-footer bg-white py-3">
        <form id="chatForm" method="POST" action="{{ $sendUrl }}">
            @csrf
            <div class="d-flex gap-2 align-items-end">
                <textarea
                    name="body"
                    class="form-control"
                    rows="2"
                    maxlength="2000"
                    placeholder="Type your message… (Enter to send, Shift+Enter for a new line)"
                    required
                ></textarea>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Send
                </button>
            </div>
        </form>
        <div id="chatError" class="alert alert-danger py-2 px-3 small mt-3 mb-0 d-none"></div>
        <noscript>
            <p class="small text-muted mb-0 mt-2">JavaScript is off, so new replies appear after you reload the page.</p>
        </noscript>
    </div>
</div>

<script>
(function () {
    const thread = document.getElementById('chatThread');
    const form = document.getElementById('chatForm');

    if (!thread || !form) {
        return;
    }

    const input = form.querySelector('textarea[name="body"]');
    const errorBox = document.getElementById('chatError');
    const tokenField = form.querySelector('input[name="_token"]');
    const token = tokenField ? tokenField.value : '';
    const feedUrl = @json($feedUrl);
    let lastId = {{ (int) (optional($messages->last())->id ?? 0) }};

    function nearBottom() {
        return thread.scrollHeight - thread.scrollTop - thread.clientHeight < 80;
    }

    function scrollToBottom() {
        thread.scrollTop = thread.scrollHeight;
    }

    function append(html) {
        if (!html) {
            return;
        }

        const stick = nearBottom();
        thread.insertAdjacentHTML('beforeend', html);

        if (stick) {
            scrollToBottom();
        }
    }

    function updateBadge(count) {
        const navBadge = document.getElementById('chatNavBadge');

        if (!navBadge) {
            return;
        }

        if (count > 0) {
            navBadge.textContent = count > 99 ? '99+' : count;
            navBadge.classList.remove('d-none');
        } else {
            navBadge.classList.add('d-none');
        }
    }

    function updateSeen(seen) {
        if (!seen || !seen.id) {
            return;
        }

        const marker = document.querySelector('#msg-' + seen.id + ' .msg-status');

        if (marker) {
            marker.textContent = seen.read_at ? 'Seen' : 'Sent';
            marker.classList.toggle('text-success', Boolean(seen.read_at));
        }
    }

    function refresh() {
        // No point polling a tab nobody is looking at.
        if (document.hidden) {
            return;
        }

        fetch(feedUrl + '?after=' + lastId, { headers: { 'Accept': 'application/json' } })
            .then((response) => (response.ok ? response.json() : null))
            .then((data) => {
                if (!data) {
                    return;
                }

                append(data.html);

                if (data.last_id) {
                    lastId = data.last_id;
                }

                updateSeen(data.seen);
                updateBadge(data.unread_count);
            })
            .catch(() => {
                // A dropped poll is not worth interrupting the conversation for.
            });
    }

    function showError(text) {
        errorBox.textContent = text;
        errorBox.classList.remove('d-none');
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const body = input.value.trim();

        if (!body) {
            return;
        }

        const payload = new FormData();
        payload.append('body', body);
        payload.append('_token', token);
        payload.append('after', lastId);

        fetch(form.action, {
            method: 'POST',
            body: payload,
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
        })
            .then(async (response) => {
                if (response.ok) {
                    return response.json();
                }

                if (response.status === 422) {
                    const errors = await response.json().catch(() => ({}));
                    const first = errors.errors && errors.errors.body ? errors.errors.body[0] : null;
                    throw new Error(first || errors.message || 'Message could not be sent.');
                }

                if (response.status === 419) {
                    throw new Error('Your session expired — please reload the page.');
                }

                throw new Error('Message could not be sent.');
            })
            .then((data) => {
                input.value = '';
                errorBox.classList.add('d-none');

                append(data.html);

                if (data.last_id) {
                    lastId = data.last_id;
                }

                scrollToBottom();
                updateBadge(data.unread_count);
            })
            .catch((error) => showError(error.message));
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });

    scrollToBottom();
    setInterval(refresh, 4000);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            refresh();
        }
    });
})();
</script>
