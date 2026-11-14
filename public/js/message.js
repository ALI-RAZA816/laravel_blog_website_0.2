$(document).ready(function () {

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });


    // Summernote Editor Initialize
    if ($('#replyEditor').length) {
        $('#replyEditor').summernote({
            height: 200, // Editor ki height
            fontNames: ['Arial', 'Georgia', 'Courier New', 'Times New Roman', 'Helvetica', 'Verdana', 'Tahoma', 'Trebuchet MS'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48'],
            toolbar: [
                ['style', ['style']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'hr']],
                ['view', ['codeview', 'help']],
                ['history', ['undo', 'redo']]
            ]
        });
    }

    var reverbKey = $('#reverbKey').val();
    var reverbHost = $('#reverbHost').val();
    var reverbPort = $('#reverbPort').val();
    var audio = new Audio('/media/notification.wav');

    $(document).one('click', function () {
        audio.play().then(() => {
            audio.pause();
            audio.currentTime = 0; 
        }).catch(err => console.log("Audio unlock failed:", err));
    });

    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: reverbHost,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: false, 
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }

    });
    
    // Variables declare karein

    function fetchMessages(page=1) {

        $.ajax({
            url: `/fetch-messages?page=${page}`,
            type: 'GET',
            success: function (data) {
                console.log(data);
                var responseAvgHours = data.response_avg || 0;
                var displayTime = '';

                if (responseAvgHours === 0) {
                    displayTime = '0 hrs';
                } else if (responseAvgHours < 1) {
                    var minutes = Math.round(responseAvgHours * 60);
                    displayTime = minutes + ' mins';
                } else if (responseAvgHours < 24) {
                    displayTime = responseAvgHours.toFixed(1) + ' hrs';
                } else {
                    var days = Math.round(responseAvgHours / 24);
                    displayTime = days + ' days';
                }

                // HTML mein update karein (jahan "4.2 hrs" likha hai)
                $('.avg-response-time').text(displayTime);
                var messages = data.messages.data;
                var total = data.totalMessages.length;
                var totalMessage = data.totalMessages;
                var unread = totalMessage.filter(message => message.status === 'unread').length;

                $('.total-inquiries').text(total);
                $('.unread-inquiries').text(unread);
                $('.totalcount').text(total);
                $('.unreadcount').text(unread);
                $('.notification-badge').text(unread);

                $('.msg-list').empty();
                $.each(messages, function (index, msg) {
                    var isUnread = msg.status === 'unread' ? 'unread' : '';
                    var unreadBadge = msg.status === 'unread' ? '<span class="badge-unread-sm">Unread</span>' : '';
                    
                    // ✅ Icons Logic: Agar unread hai to "Read" (band envelope) ka icon, agar read hai to "Unread" (open envelope) ka icon
                    var iconStatus = msg.status === 'unread' 
                        ? `<i class="fa-regular fa-envelope toggle-status mark-read" data-id="${msg.id}" data-status="read" title="Mark as Read"></i>` 
                        : `<i class="fa-regular fa-envelope-open toggle-status mark-unread" data-id="${msg.id}" data-status="unread" title="Mark as Unread"></i>`;

                    var msgHtml = `
                        <div class="msg-item ${isUnread}" id="msg-${msg.id}" data-id="${msg.id}">
                            <div class="msg-item-top">
                                <p class="msg-item-sender">${esc(msg.name)} ${unreadBadge}</p>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="msg-item-date">${msg.date}</span>
                                    
                                    <!-- ✅ Hover par dikhne wale Action Icons -->
                                    <div class="msg-actions">
                                        ${iconStatus}
                                        <i class="fa-regular fa-trash-can delete-message" data-id="${msg.id}" title="Delete Message"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="msg-item-subject">${esc(msg.subject)}</p>
                            <p class="msg-item-excerpt${esc(msg.message.substring(0,80))}...</p>
                            <p class="msg-item-meta">${esc(msg.email)}&bull; ${msg.reply === 'replied' ? 'Replied' : 'Not replied'}</p>
                        </div>
                    `;
                    $('.msg-list').append(msgHtml);
                });

                $('.msg-sidebar-pagination').empty();
                
                var row = '';

                // Previous Button
                row += `<button class="page-arrow" id="prevPageBtn" data-prevpage = ${data.messages.current_page - 1}><i class="fa-solid fa-chevron-left"></i></button>`;
                // Pagination Text
                row += `<span id="paginationText">${data.messages.from} to ${data.messages.to} of ${data.messages.total} inquiries</span>`
                // NEXT Button
                row += `<button class="page-arrow" id="nextPageBtn" data-nextpage = ${data.messages.current_page + 1}><i class="fa-solid fa-chevron-right"></i></button>`;
                
                $('.msg-sidebar-pagination').append(row);

                if(page <=1){
                    $('#prevPageBtn').prop('disabled',true);
                }else{
                    $('#prevPageBtn').prop('disabled',false);
                }
                if(page >= data.messages.last_page){
                    $('#nextPageBtn').prop('disabled',true);
                }else{
                    $('#nextPageBtn').prop('disabled',false);
                }


            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }
    fetchMessages(1);

    $(document).on('click','#prevPageBtn',function(e){
        e.preventDefault();
        var page = $(this).data('prevpage');
        // if(page <){
            fetchMessages(page);
        // }
    });

    $(document).on('click','#nextPageBtn',function(e){
        e.preventDefault();
        var page = $(this).data('nextpage');
        if(page >= 1){
            fetchMessages(page);
        }
    });

    window.Echo.private('admin-messages').listen('.NewMessageReceived', (e) => {
            
            var newMsg = e.message;

            let currentTotal = parseInt($('.total-inquiries').text()) || 0;
            let currentUnread = parseInt($('.unread-inquiries').text()) || 0;
            let unreadCount = parseInt($('.unreadcount').text()) || 0;
            let totalCount = parseInt($('.totalcount').text()) || 0;
            
            $('.total-inquiries').text(currentTotal + 1);
            $('.unread-inquiries').text(currentUnread + 1);
            $('.all-count').text(currentTotal + 1);
            $('.unread-count').text(currentUnread + 1);
            $('.unreadcount').text(unreadCount + 1);
            $('.notification-badge').text(unreadCount + 1);
            $('.totalcount').text(totalCount + 1);

            let newMsgHtml = `
                <div class="msg-item unread" id="msg-${newMsg.id}">
                    <div class="msg-item-top">
                        <p class="msg-item-sender">${esc(newMsg.name)} <span class="badge-unread-sm">Unread</span></p>
                        <span class="msg-item-date">Just now</span>
                    </div>
                    <p class="msg-item-subject">${esc(newMsg.subject)}</p>
                    <p class="msg-item-excerpt">${esc(newMsg.message.substring(0, 80))}...</p>
                    <p class="msg-item-meta">${esc(newMsg.email)} &bull; Not replied</p>
                </div>
            `;
            $('.msg-list').prepend(newMsgHtml);
            
            // (C) Notification Sound (Optional)
            audio.play().catch(err => {
                console.log("Sound play blocked by browser:", err);
            });

    }).listen('.MessageStatusUpdated', (e)=>{
        console.log(e);
        
        let msgId = e.message.id;
        let newStatus = e.message.status;
        let currentBadge = parseInt($('.notification-badge').text()) || 0;
        let unreadInquiries = parseInt($('.unread-inquiries').text()) || 0;
        let tabUnread = parseInt($('.unreadcount').text()) || 0;
        
        // ✅ Counters ko update karein
        if (newStatus === 'read') {
            if (currentBadge > 0) {
                $('.notification-badge').text(currentBadge - 1);
                if(currentBadge - 1 === 0) $('.notification-badge').hide();
            }
            if (unreadInquiries > 0) $('.unread-inquiries').text(unreadInquiries - 1);
            if (tabUnread > 0) $('.unreadcount').text(tabUnread - 1);

        } else {
            $('.notification-badge').text(currentBadge + 1).show();
            $('.unread-inquiries').text(unreadInquiries + 1);
            $('.unreadcount').text(tabUnread + 1);
        }

        // ✅ Card ki styling update karein
        let $msgItem = $('#msg-' + msgId);
        if ($msgItem.length) {
            if (newStatus === 'read') {
                $msgItem.removeClass('unread'); 
                $msgItem.find('.badge-unread-sm').remove(); 
                $msgItem.find('.msg-item-sender').css('font-weight', 'normal');
            } else {
                $msgItem.addClass('unread');
                if(!$msgItem.find('.badge-unread-sm').length) {
                    $msgItem.find('.msg-item-sender').prepend('<span class="badge-unread-sm">Unread</span>');
                }
                $msgItem.find('.msg-item-sender').css('font-weight', '700');
            }
        }
    });

    // ============================================
    // SHOW MESSAGE DETAILS (Right Panel)
    // ============================================

    $(document).on('click', '.msg-item', function(e) {

        if ($(e.target).closest('.msg-actions').length) return;

         var messageId = $(this).attr('data-id'); 
        
        if (!messageId || messageId === 'undefined') {
            console.error("Message ID is missing on this card!");
            return; 
        }
        
        $('.msg-item').removeClass('active');
        $(this).addClass('active');

        $.ajax({
            url: `/fetch-single-message/${messageId}`, 
            type: 'GET',
            success: function(data) {
                console.log(data);
                var msg = data.message; 

                var nameParts = (msg.name || 'Unknown').trim().split(/\s+/);
                var firstLetter = (nameParts[0] || '').charAt(0).toUpperCase();
                var secondLetter = (nameParts[1] || '').charAt(0).toUpperCase();
                var initials = firstLetter + secondLetter;

                $('.msg-detail-content .avatar-circle').text(initials);
                $('.msg-detail-content .sender-info h4').text(msg.name);
                $('.msg-detail-content .sender-info p').text(msg.email);
                $('.msg-detail-content .email-subject').text(msg.subject);
                $('.msg-detail-content .email-body').html(`<p>${esc(msg.message)}</p>`);
                

                if(msg.status === 'read') {
                    $('#msg-' + messageId).removeClass('unread');
                    $('#msg-' + messageId).find('.badge-unread-sm').remove();
                }
            },
            error: function(xhr) {
                console.log(xhr);
                alert('Failed to load message details');
            }
        });
    });


        // ============================================
    // MARK AS READ (Jab unread message par envelope icon click ho)
    // ============================================
    $(document).on('click', '.mark-read', function(e) {
        e.preventDefault();
        e.stopPropagation(); 

        var $icon = $(this);
        var messageId = $icon.data('id');

        $.ajax({
            url: `/mark-as-read/${messageId}`,
            type: 'POST', 
            data: { _method: 'PUT' },
            success: function(res) {
                // ✅ Sirf Icon badlein, count Reverb khud update karega
                $icon.removeClass('mark-read fa-envelope').addClass('mark-unread fa-envelope-open')
                     .attr('title', 'Mark as Unread');
                
                var $msgCard = $('#msg-' + messageId);
                $msgCard.removeClass('unread');
                $msgCard.find('.badge-unread-sm').remove();
                $msgCard.find('.msg-item-sender').css('font-weight', 'normal');
            },
            error: function(xhr) {
                console.log(xhr);
                alert('Failed to mark as read');
            }
        });
    });

        // ============================================
    // MARK AS UNREAD (Jab read message par open-envelope icon click ho)
    // ============================================
    $(document).on('click', '.mark-unread', function(e) {
        e.preventDefault();
        e.stopPropagation(); 

        var $icon = $(this);
        var messageId = $icon.data('id');

        $.ajax({
            url: `/mark-as-unread/${messageId}`,
            type: 'POST', 
            data: { _method: 'PUT' },
            success: function(res) {
                // ✅ Sirf Icon badlein, count Reverb khud update karega
                $icon.removeClass('mark-unread fa-envelope-open').addClass('mark-read fa-envelope')
                     .attr('title', 'Mark as Read');
                
                var $msgCard = $('#msg-' + messageId);
                $msgCard.addClass('unread');
                // $msgCard.find('.msg-item-sender').prepend('<span class="badge-unread-sm">Unread</span>'); // Badge wapas
                $msgCard.find('.msg-item-sender').css('font-weight', '700');
            },
            error: function(xhr) {
                console.log(xhr);
                alert('Failed to mark as unread');
            }
        });
    });


        // ============================================
    // DELETE MESSAGE (Hover Icon Click)
    // ============================================
    $(document).on('click', '.delete-message', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Taake message open na ho

        var $icon = $(this);
        var messageId = $icon.data('id');
        var $msgCard = $('#msg-' + messageId);

        // Confirm dialog
        if (!confirm('Kya aap waqai yeh message delete karna chahte hain?')) {
            return;
        }

        // Disable icon taake double click na ho
        $icon.css('pointer-events', 'none').css('opacity', '0.5');

        $.ajax({
            url: `/delete-message/${messageId}`, // ✅ Apna delete route yahan dein
            type: 'DELETE',
            success: function(res) {
                // 1. Card ko smoothly remove karein
                $msgCard.fadeOut(300, function() {
                    $(this).remove();
                });

                let isUnread = $msgCard.hasClass('unread');
                
                let currentTotal = parseInt($('.total-inquiries').text()) || 0;
                if (currentTotal > 0) {
                    $('.total-inquiries').text(currentTotal - 1);
                    $('.totalcount').text(currentTotal - 1); // Tab ka count
                }

                // Agar Unread tha, to Unread count bhi kam karein
                if (isUnread) {
                    let currentUnread = parseInt($('.unread-inquiries').text()) || 0;
                    let tabUnread = parseInt($('.unreadcount').text()) || 0;
                    let badge = parseInt($('.notification-badge').text()) || 0;

                    if (currentUnread > 0) $('.unread-inquiries').text(currentUnread - 1);
                    if (tabUnread > 0) $('.unreadcount').text(tabUnread - 1);
                    if (badge > 0) {
                        $('.notification-badge').text(badge - 1);
                        if(badge - 1 === 0) $('.notification-badge').hide();
                    }
                }

                // 3. Right panel ko khali karein agar wahi message open tha
                if ($msgCard.hasClass('active')) {
                    $('.msg-detail-content .avatar-circle').text('');
                    $('.msg-detail-content .sender-info h4').text('');
                    $('.msg-detail-content .sender-info p').text('');
                    $('.msg-detail-content .email-subject').text('');
                    $('.msg-detail-content .email-body').html('');
                }
            },
            error: function(xhr) {
                console.log(xhr);
                alert('Failed to delete message');
                // Icon wapas enable karein agar error aaye
                $icon.css('pointer-events', 'auto').css('opacity', '1');
            }
        });
    });

    // ============================================
    // SEND REPLY (Summernote Editor)
    // ============================================
    $(document).on('click', '.btn-send-reply', function(e) {
        e.preventDefault();

        var $btn = $(this);
        var messageId = $('.msg-item.active').data('id'); // Jo message left side se active hai

        if (!messageId) {
            alert('Please select a message first.');
            return;
        }

        var replyHtml = $('#replyEditor').summernote('code');

        if (!replyHtml || replyHtml === '<p><br></p>' || replyHtml.trim() === '') {
            alert('Please write a reply before sending.');
            return;
        }

        var originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Sending...');

        $.ajax({
            url: '/send-reply',
            type: 'POST',
            data: {
                id: messageId,
                reply: replyHtml
            },
            success: function(res) {
                alert(res.message); 

                $('#replyEditor').summernote('code', '');

                var $activeMsg = $('.msg-item.active');
                $activeMsg.find('.msg-item-meta').text(($activeMsg.data('email') || ''));
                
                fetchMessages(1);
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message); 
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    alert(xhr.responseJSON.errors.reply[0]);
                } else {
                    alert('Something went wrong');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    
});