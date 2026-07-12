<script>
(function() {
    let pins = @json($approvedComments);
    let activePin = null;
    let pendingData = { x: 0, y: 0, element_selector: null, element_xpath: null, offset_x: 0, offset_y: 0 };
    let isReply = false;
    let replyToPinId = null;
    const commentStoreUrl = '{{ $commentStoreUrl }}';
    const csrfToken = '{{ $csrfToken }}';
    const loginUrl = '{{ $loginUrl }}';
    const isLoggedIn = {{ $isLoggedIn }};
    
    const pinsContainer = document.getElementById('pinsContainer');
    const commentModal = document.getElementById('commentModal');
    const modalTitle = document.getElementById('modalTitle');
    const commentTextarea = document.getElementById('commentTextarea');
    const cancelBtn = document.getElementById('cancelBtn');
    const submitBtn = document.getElementById('submitBtn');
    const contextMenu = document.getElementById('contextMenu');
    const addCommentBtn = document.getElementById('addCommentBtn');
    const toastContainer = document.getElementById('toastContainer');
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast ' + type;
        const icon = type === 'success' ? '✅' : '❌';
        toast.innerHTML = '<span class="icon">' + icon + '</span><span class="message">' + message + '</span><button class="close">&times;</button>';
        
        const closeBtn = toast.querySelector('.close');
        closeBtn.addEventListener('click', function() {
            removeToast(toast);
        });
        
        toastContainer.appendChild(toast);
        
        setTimeout(function() {
            removeToast(toast);
        }, 4000);
    }
    
    function removeToast(toast) {
        toast.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(function() {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
    
    function init() {
        renderPins();
        setupEventListeners();
    }
    
    function setupEventListeners() {
        document.addEventListener('contextmenu', handleRightClick);
        document.addEventListener('click', hideContextMenu);
        cancelBtn.addEventListener('click', closeModal);
        submitBtn.addEventListener('click', submitComment);
        addCommentBtn.addEventListener('click', openAddCommentModal);
        window.addEventListener('resize', updateAllPinPositions);
        window.addEventListener('scroll', updateAllPinPositions, { passive: true });
    }
    
    function getElementByXPath(xpath) {
        try {
            const result = document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null);
            return result.singleNodeValue;
        } catch (err) {
            console.error('Error finding element by XPath:', err);
            return null;
        }
    }
    
    function calculatePinPosition(pin) {
        const pageHeight = Math.max(
            document.documentElement.scrollHeight,
            document.documentElement.offsetHeight,
            document.body.scrollHeight,
            document.body.offsetHeight
        );
        const pageWidth = Math.max(
            document.documentElement.scrollWidth,
            document.documentElement.offsetWidth,
            document.body.scrollWidth,
            document.body.offsetWidth
        );
        
        let left = pin.x * 100;
        let top = pin.y * 100;
        let targetElement = null;
        
        if (pin.element_xpath) {
            try {
                targetElement = getElementByXPath(pin.element_xpath);
            } catch (err) {
                console.error('Error finding element by XPath for pin ' + pin.id + ':', err);
            }
        }
        
        if (!targetElement && pin.element_selector) {
            try {
                const escapedSelector = pin.element_selector.replace(/\.([^\s.:#\[]+)/g, function(m, c) {
                    return '.' + escapeCssIdentifier(c);
                }).replace(/#([^\s.:#\[]+)/g, function(m, i) {
                    return '#' + escapeCssIdentifier(i);
                });
                targetElement = document.querySelector(escapedSelector);
            } catch (err) {
                console.error('Error finding element by selector for pin ' + pin.id + ':', err);
            }
        }
        
        if (targetElement) {
            const rect = targetElement.getBoundingClientRect();
            left = (rect.left + (rect.width * (pin.offset_x || 0.5))) / window.innerWidth * 100;
            top = (rect.top + window.scrollY + (rect.height * (pin.offset_y || 0.5))) / pageHeight * 100;
        }
        
        return { left: left, top: top };
    }
    
    function updateAllPinPositions() {
        const pageHeight = Math.max(
            document.documentElement.scrollHeight,
            document.documentElement.offsetHeight,
            document.body.scrollHeight,
            document.body.offsetHeight
        );
        const pageWidth = Math.max(
            document.documentElement.scrollWidth,
            document.documentElement.offsetWidth,
            document.body.scrollWidth,
            document.body.offsetWidth
        );
        pinsContainer.style.height = pageHeight + 'px';
        pinsContainer.style.width = pageWidth + 'px';
        
        pins.forEach(function(pin, index) {
            const pinEl = pinsContainer.children[index];
            if (!pinEl) return;
            
            const pos = calculatePinPosition(pin);
            pinEl.style.left = pos.left + '%';
            pinEl.style.top = pos.top + '%';
        });
    }
    
    function escapeCssIdentifier(str) {
        if (typeof CSS !== 'undefined' && CSS.escape) {
            return CSS.escape(str);
        }
        return str.replace(/([!\"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~])/g, '\\\\$1');
    }
    
    function getElementSelector(element) {
        if (element.id) return '#' + escapeCssIdentifier(element.id);
        let path = [];
        while (element && element.nodeType === Node.ELEMENT_NODE) {
            let selector = element.tagName.toLowerCase();
            if (element.className && typeof element.className === 'string') {
                let classes = element.className.trim().split(/\s+/).filter(Boolean).slice(0, 2);
                if (classes.length > 0) {
                    selector += '.' + classes.map(escapeCssIdentifier).join('.');
                }
            }
            
            let index = 0;
            let sibling = element.previousElementSibling;
            while (sibling) {
                if (sibling.tagName === element.tagName) {
                    index++;
                }
                sibling = sibling.previousElementSibling;
            }
            
            if (index > 0) {
                selector += ':nth-of-type(' + (index + 1) + ')';
            }
            
            path.unshift(selector);
            if (element.id) {
                path = ['#' + escapeCssIdentifier(element.id)];
                break;
            }
            element = element.parentNode;
        }
        return path.join(' > ');
    }
    
    function getElementXPath(element) {
        if (!element) return null;
        if (element.id) return '//*[@id="' + element.id + '"]';
        let parts = [];
        while (element && element.nodeType === Node.ELEMENT_NODE) {
            let index = 0;
            let sibling = element.previousSibling;
            while (sibling) {
                if (sibling.nodeType === Node.ELEMENT_NODE && sibling.tagName === element.tagName) index++;
                sibling = sibling.previousSibling;
            }
            let tagName = element.tagName.toLowerCase();
            parts.unshift(tagName + (index > 0 ? '[' + (index + 1) + ']' : ''));
            element = element.parentNode;
        }
        return '/' + parts.join('/');
    }
    
    function handleRightClick(e) {
        e.preventDefault();
        const targetElement = e.target;
        const elementSelector = getElementSelector(targetElement);
        const elementXPath = getElementXPath(targetElement);
        
        const pageWidth = Math.max(document.documentElement.scrollWidth, document.documentElement.offsetWidth, document.body.scrollWidth, document.body.offsetWidth);
        const pageHeight = Math.max(document.documentElement.scrollHeight, document.documentElement.offsetHeight, document.body.scrollHeight, document.body.offsetHeight);
        
        const x = e.clientX / window.innerWidth;
        const y = (e.clientY + window.scrollY) / pageHeight;
        
        const rect = targetElement.getBoundingClientRect();
        const offsetX = (e.clientX - rect.left) / rect.width;
        const offsetY = (e.clientY - rect.top) / rect.height;
        
        pendingData = {
            x: x,
            y: y,
            element_selector: elementSelector,
            element_xpath: elementXPath,
            offset_x: offsetX,
            offset_y: offsetY
        };
        
        let left = e.clientX;
        let top = e.clientY;
        
        contextMenu.style.display = 'block';
        const contextMenuRect = contextMenu.getBoundingClientRect();
        
        if (left + contextMenuRect.width > window.innerWidth) {
            left = window.innerWidth - contextMenuRect.width - 10;
        }
        
        if (top + contextMenuRect.height > window.innerHeight) {
            top = window.innerHeight - contextMenuRect.height - 10;
        }
        
        contextMenu.style.left = left + 'px';
        contextMenu.style.top = top + 'px';
    }
    
    function hideContextMenu() {
        contextMenu.style.display = 'none';
    }
    
    function openAddCommentModal() {
        hideContextMenu();
        isReply = false;
        replyToPinId = null;
        modalTitle.textContent = 'Add Comment';
        commentTextarea.value = '';
        commentModal.style.display = 'flex';
    }
    
    function renderPins() {
        const pageHeight = Math.max(
            document.documentElement.scrollHeight,
            document.documentElement.offsetHeight,
            document.body.scrollHeight,
            document.body.offsetHeight
        );
        const pageWidth = Math.max(
            document.documentElement.scrollWidth,
            document.documentElement.offsetWidth,
            document.body.scrollWidth,
            document.body.offsetWidth
        );
        pinsContainer.style.height = pageHeight + 'px';
        pinsContainer.style.width = pageWidth + 'px';
        pinsContainer.innerHTML = '';
        
        pins.forEach(function(pin) {
            const pinEl = document.createElement('div');
            
            const pos = calculatePinPosition(pin);
            
            pinEl.style.cssText = 'position:absolute;left:' + pos.left + '%;top:' + pos.top + '%;z-index:9997;pointer-events:auto;';
            var pinUserName = pin.user ? (pin.user.username || pin.user.name) : 'Anonymous';
            var repliesHtml = '';
            if (pin.replies && pin.replies.length > 0) {
                repliesHtml = '<div style="border-top:1px solid #e5e7eb;padding-top:12px;margin-top:12px;"><div style="font-size:14px;font-weight:600;color:#4b5563;margin-bottom:8px;">Replies</div>';
                pin.replies.forEach(function(reply) {
                    var replyUserName = reply.user ? (reply.user.username || reply.user.name) : 'Anonymous';
                    repliesHtml += '<div style="background:#f3f4f6;border-radius:6px;padding:12px;margin-bottom:8px;"><div style="font-weight:500;color:#1f2937;font-size:14px;">' + replyUserName + '</div><div style="color:#374151;font-size:14px;margin-top:4px;">' + reply.text + '</div></div>';
                });
                repliesHtml += '</div>';
            }
            pinEl.innerHTML = '<button class="pin-btn" data-pin-id="' + pin.id + '" style="transform:translate(-50%,-100%);background:#ef4444;color:white;border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.2);">!</button><div class="pin-popup" data-popup-id="' + pin.id + '" style="display:none;position:absolute;transform:translate(8px,-50%);z-index:9999;min-width:280px;pointer-events:auto;"><div style="background:white;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,0.2);border:1px solid #e5e7eb;padding:16px;"><div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;"><div style="font-weight:600;color:#1f2937;">' + pinUserName + '</div><button class="close-popup-btn" data-popup-id="' + pin.id + '" style="background:none;border:none;cursor:pointer;color:#9ca3af;"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div><div style="color:#374151;margin-bottom:16px;">' + pin.text + '</div>' + repliesHtml + '<button class="reply-btn" data-pin-id="' + pin.id + '" style="background:none;border:none;color:#3b82f6;font-weight:500;cursor:pointer;padding:0;">↩️ Reply</button></div></div>';
            pinsContainer.appendChild(pinEl);
        });
        
        document.querySelectorAll('.pin-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const pinId = btn.dataset.pinId;
                togglePinPopup(pinId);
            });
        });
        
        document.querySelectorAll('.close-popup-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const popupId = btn.dataset.popupId;
                closePinPopup(popupId);
            });
        });
        
        document.querySelectorAll('.reply-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const pinId = btn.dataset.pinId;
                openReplyModal(pinId);
            });
        });
    }
    
    function togglePinPopup(pinId) {
        const popup = document.querySelector('[data-popup-id="' + pinId + '"]');
        if (popup.style.display === 'block') {
            popup.style.display = 'none';
        } else {
            document.querySelectorAll('.pin-popup').forEach(function(p) { p.style.display = 'none'; });
            popup.style.display = 'block';
            
            const pinBtn = popup.previousElementSibling;
            const popupRect = popup.getBoundingClientRect();
            const pinBtnRect = pinBtn.getBoundingClientRect();
            
            let translateX = 8;
            let translateY = -50;
            
            if (pinBtnRect.right + popupRect.width > window.innerWidth) {
                translateX = -(8 + popupRect.width);
            }
            
            if (pinBtnRect.top + popupRect.height / 2 > window.innerHeight) {
                translateY = -(100 + 8);
            } else if (pinBtnRect.top - popupRect.height / 2 < 0) {
                translateY = 8;
            }
            
            popup.style.transform = 'translate(' + translateX + 'px,' + translateY + '%)';
        }
    }
    
    function closePinPopup(pinId) {
        const popup = document.querySelector('[data-popup-id="' + pinId + '"]');
        popup.style.display = 'none';
    }
    
    function openReplyModal(pinId) {
        const pin = pins.find(function(p) { return p.id == pinId; });
        replyToPinId = pinId;
        pendingData = { x: pin.x, y: pin.y, element_selector: pin.element_selector, element_xpath: pin.element_xpath, offset_x: pin.offset_x, offset_y: pin.offset_y };
        isReply = true;
        modalTitle.textContent = 'Reply to Comment';
        commentTextarea.value = '';
        commentModal.style.display = 'flex';
    }
    
    function closeModal() {
        commentModal.style.display = 'none';
    }
    
    async function submitComment() {
        const text = commentTextarea.value.trim();
        if (!text) return;
        
        if (!isLoggedIn) {
            window.location.href = loginUrl;
            return;
        }
        
        const data = {
            text: text,
            x: pendingData.x,
            y: pendingData.y,
            element_selector: pendingData.element_selector,
            element_xpath: pendingData.element_xpath,
            offset_x: pendingData.offset_x,
            offset_y: pendingData.offset_y
        };
        
        if (isReply && replyToPinId) {
            data.parent_id = replyToPinId;
        }
        
        try {
            const response = await fetch(commentStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                const result = await response.json();
                showToast('Comment submitted and pending approval!', 'success');
                closeModal();
            } else {
                showToast('Failed to submit comment', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Failed to submit comment', 'error');
        }
    }
    
    init();
    
    setTimeout(function() {
        updateAllPinPositions();
    }, 500);
})();
</script>
