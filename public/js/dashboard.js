$(document).ready(function() {

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });

    var viewsChartInstance = null;


    function dashboard(){
        $.ajax({
            url: "/dashboardData",
            type: "GET",
            success: function(response){
                console.log(response);
                $('.commentsAvg').text(`+${response.average}%`);
                $('.postAvg').text(`+${response.velocity}%`);
                $('.avgViews').text(`+${response.velocity}%`);
                $('.total-posts').text(fmt(response.total_posts));
                $('.total-comments').text(fmt(response.total_comments));
                $('.total-users').text(response.total_users === null ? '-' : fmt(response.total_users));
                $('.total-views').text(fmt(response.views));

                var recentCommnents = response.recent_comments;
                var recentPosts = response.recent_posts;
                $('.recent-comment-list-item').empty();
                $.each(recentCommnents, function(index, comment){
                    var nameParts    = (comment.user.name || '').trim().split(/\s+/);
                    var firstletter  = (nameParts[0] || '').charAt(0).toUpperCase();
                    var secondLetter = (nameParts[1] || '').charAt(0).toUpperCase();
                    var commentItem = `
                        <div class="comment-list-item">`
                         if(comment.user.image){
                                    commentItem += `<div style='height:40px;width:40px;' class='overflow-hidden rounded-5'><img  style="width:100%;height:100%;object-fit:cover;display:block;" src='/uploads/${comment.user.image}'/></div>
                                            `;
                                }else{
                                    commentItem += `<div class="avatar-initials">${esc(firstletter)}${esc(secondLetter)}</div>`
                                }
                                commentItem += `
                            <div class="comment-content">
                                <p class="comment-author-name">${esc(comment.user.name)}</p>
                                <p class="comment-excerpt">${esc(comment?.comment?.substring(0, 20))}...</p>
                            </div>
                        </div>
                    `;
                    $('.recent-comment-list-item').append(commentItem);
                });

                $('.recent-post-list-item').empty();
                $.each(recentPosts, function(index, post){
                    var nameParts    = (post.author.name || '').trim().split(/\s+/);
                    var firstletter  = (nameParts[0] || '').charAt(0).toUpperCase();
                    var secondLetter = (nameParts[1] || '').charAt(0).toUpperCase();
                    var postItem = `<tr>
                        <td>
                            <p class="post-title-main">${esc(post.title)}</p>
                            <p class="post-slug">${esc(post.category.slug)}</p>
                        </td>
                        <td><span class="badge-category">${esc(post.category.name)}</span></td>
                        <td>
                            <div class="d-flex align-items-center">`
                             if(post.author.image){
                                    postItem += `<div style='height:40px;width:40px;' class='overflow-hidden rounded-5 me-2'><img  style="width:100%;height:100%;object-fit:cover;display:block;" src='/uploads/${post.author.image}'/></div>
                                            `;
                                }else{
                                    postItem += `<div class="avatar-initials">${esc(firstletter)}${esc(secondLetter)}</div>`
                                }
                                postItem += `
                                <span style="font-size: 0.85rem; font-weight: 500;">${esc(post.author.name)}</span>
                            </div>
                        </td>
                        <td><span class="status-published text-capitalize">${esc(post.published)}</span></td>
                        <td style="font-size: 0.85rem; color: #555;">${esc(post.date)}</td>
                        <td><a href="/post-preview?postid=${post.id}"><i class="fa-regular fa-eye action-icon"></i></a></td>
                    </tr>`;
                    $('.recent-post-list-item').append(postItem);
                });
                renderChart(response.last_month, 'Last 12 Months');
            },
            error: function(xhr){
                console.error(xhr);
            }
        })
    }

    function renderChart(chartData, filterType) {
        if (!chartData || chartData.length === 0) return;
 
        var labels = [];
        var data = [];

        var monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        var displayData = chartData;
        if (filterType == 6) {
            displayData = chartData.slice(-6);
        }else{
             displayData = chartData.slice(-12);
         }
 
        $.each(displayData, function(index, item) {
            var monthName = monthNames[item.month] || 'N/A'; 
            
            labels.push(monthName + ' ' + item.year); 
            
            data.push(item.monthly_views); 
        });
 
        const ctx = document.getElementById('viewsChart').getContext('2d');
         
        if (viewsChartInstance) {
             viewsChartInstance.destroy();
        }
 
         // Chart Gradient
         let gradient = ctx.createLinearGradient(0, 0, 0, 300);
         gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
         gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
 
         viewsChartInstance = new Chart(ctx, {
             type: 'line',
             data: {
                 labels: labels, // ✅ (Jan 2026, Feb 2026)
                 datasets: [{
                     label: 'Views',
                     data: data, // ✅ (1500, 2000)
                     borderColor: '#3b82f6',
                     backgroundColor: gradient,
                     borderWidth: 2,
                     pointBackgroundColor: '#3b82f6',
                     pointBorderColor: '#fff',
                     pointBorderWidth: 2,
                     pointRadius: 4,
                     pointHoverRadius: 6,
                     fill: true,
                     tension: 0.3
                 }]
             },
             options: {
                 responsive: true,
                 maintainAspectRatio: false,
                 plugins: {
                     legend: { display: false }
                 },
                 scales: {
                     y: {
                         beginAtZero: true,
                         ticks: { color: '#9ca3af', font: { size: 11, family: 'Inter' } },
                         grid: { color: '#f3f4f6', drawBorder: false }
                     },
                     x: {
                         ticks: { color: '#9ca3af', font: { size: 11, family: 'Inter' } },
                         grid: { display: false }
                     }
                 }
             }
         });
     }
 
    $('.form-select-custom').on('change', function () {
        var selectedFilter = $(this).val(); 

        $.ajax({
            url: "/dashboardData",
            type: "GET",
            success: function (response) {
                renderChart(response.last_month, selectedFilter);
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });

    });
    dashboard();




});