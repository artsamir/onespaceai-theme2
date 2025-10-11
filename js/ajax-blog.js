/**
 * AJAX Blog Functionality
 * Handles dynamic loading of blog content without page refresh
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

(function($) {
    'use strict';

    let isLoading = false;
    let originalContent = '';

    $(document).ready(function() {
        initAjaxBlog();
    });

    function initAjaxBlog() {
        // Store original content when on home page
        if ($('.home-post-grid').length) {
            originalContent = $('.home-main').html();
        }

        // Handle Read More button clicks
        $(document).on('click', '.read-more-btn', handleReadMore);
        
        // Handle sidebar links
        $(document).on('click', '.ajax-blog-link', handleSidebarLink);
        
        // Handle back to grid button
        $(document).on('click', '.ajax-back-to-grid', handleBackToGrid);
        
        // Handle browser back button
        window.addEventListener('popstate', handlePopState);
        
        // Push initial state
        if ($('.home-post-grid').length) {
            const initialState = {
                type: 'grid',
                url: window.location.href
            };
            history.replaceState(initialState, '', window.location.href);
        }
    }

    function handleReadMore(e) {
        e.preventDefault();
        
        if (isLoading) return;
        
        const $link = $(this);
        const url = $link.attr('href');
        const postId = $link.data('post-id') || extractPostId(url);
        
        if (!postId) return;
        
        loadBlogContent(postId, url);
    }

    function handleSidebarLink(e) {
        e.preventDefault();
        
        if (isLoading) return;
        
        const $link = $(this);
        const url = $link.attr('href');
        const postId = $link.data('post-id') || extractPostId(url);
        
        if (!postId) return;
        
        loadBlogContent(postId, url);
    }

    function handleBackToGrid(e) {
        e.preventDefault();
        showPostGrid();
    }

    function handlePopState(e) {
        if (e.state) {
            if (e.state.type === 'grid') {
                showPostGrid(false);
            } else if (e.state.type === 'post' && e.state.postId) {
                loadBlogContent(e.state.postId, e.state.url, false);
            }
        }
    }

    function extractPostId(url) {
        // Extract post ID from various URL formats
        const match = url.match(/(?:\/(\d+)\/|[?&]p=(\d+)|\/([^\/]+)\/$)/);
        if (match) {
            return match[1] || match[2] || getPostIdBySlug(match[3]);
        }
        return null;
    }

    function getPostIdBySlug(slug) {
        // This would need to be enhanced to actually get post ID by slug
        // For now, we'll rely on the data attribute approach
        return null;
    }

    function loadBlogContent(postId, url, updateHistory = true) {
        if (isLoading) return;
        
        isLoading = true;
        showLoader();
        
        $.ajax({
            url: onespace_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'load_blog_content',
                post_id: postId,
                nonce: onespace_ajax.nonce
            },
            success: function(response) {
                console.log('AJAX Response:', response); // Debug log
                if (response.success) {
                    displayBlogContent(response.data);
                    
                    if (updateHistory) {
                        const state = {
                            type: 'post',
                            postId: postId,
                            url: url
                        };
                        history.pushState(state, '', url);
                    }
                    
                    // Scroll to top smoothly
                    $('html, body').animate({ scrollTop: 0 }, 300);
                } else {
                    console.error('Failed to load blog content:', response);
                    // Fallback to normal navigation
                    window.location.href = url;
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', xhr.responseText);
                // Fallback to normal navigation
                window.location.href = url;
            },
            complete: function() {
                isLoading = false;
                hideLoader();
            }
        });
    }

    function displayBlogContent(content) {
        const $main = $('.home-main');
        $main.addClass('loading-content');
        
        setTimeout(function() {
            $main.html(content);
            $main.removeClass('loading-content').addClass('single-post-view');
            
            // Add fade in effect
            $main.find('.single-post-article').hide().fadeIn(400);
        }, 150);
    }

    function showPostGrid(updateHistory = true) {
        if (!originalContent) {
            window.location.href = '/';
            return;
        }
        
        const $main = $('.home-main');
        $main.addClass('loading-content');
        
        setTimeout(function() {
            $main.html(originalContent);
            $main.removeClass('loading-content single-post-view');
            
            if (updateHistory) {
                const state = {
                    type: 'grid',
                    url: '/'
                };
                history.pushState(state, '', '/');
            }
            
            // Add fade in effect
            $main.find('.home-post-grid').hide().fadeIn(400);
        }, 150);
    }

    function showLoader() {
        const $main = $('.home-main');
        
        if (!$main.find('.ajax-loader').length) {
            const loader = `
                <div class="ajax-loader">
                    <div class="loader-spinner"></div>
                    <div class="loader-text">Loading...</div>
                </div>
            `;
            $main.append(loader);
        }
        
        $main.find('.ajax-loader').fadeIn(200);
    }

    function hideLoader() {
        $('.ajax-loader').fadeOut(200, function() {
            $(this).remove();
        });
    }

    // Add data attributes to posts for easier AJAX handling
    $(document).ready(function() {
        $('.post-card').each(function() {
            const $card = $(this);
            const postId = $card.attr('id').replace('post-', '');
            $card.find('.read-more-btn').attr('data-post-id', postId);
        });
    });

    // Reading Timer functionality
    let readingStartTime = null;
    let readingTimer = null;

    function startReadingTimer() {
        readingStartTime = Date.now();
        readingTimer = setInterval(updateReadingTime, 1000);
    }

    function stopReadingTimer() {
        if (readingTimer) {
            clearInterval(readingTimer);
            readingTimer = null;
        }
        readingStartTime = null;
    }

    function updateReadingTime() {
        if (!readingStartTime) return;
        
        const elapsed = Date.now() - readingStartTime;
        const minutes = Math.floor(elapsed / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);
        
        const $timer = $('#reading-timer');
        if ($timer.length) {
            if (minutes > 0) {
                $timer.text(`${minutes}m ${seconds}s`);
            } else {
                $timer.text(`${seconds}s`);
            }
        }
    }

    // Start/stop reading timer based on content
    function handleReadingTimer() {
        if ($('.single-post-article').length) {
            startReadingTimer();
        } else {
            stopReadingTimer();
        }
    }

    // Override display function to include reading timer
    const originalDisplayBlogContent = displayBlogContent;
    displayBlogContent = function(content) {
        originalDisplayBlogContent(content);
        handleReadingTimer();
    };

    const originalShowPostGrid = showPostGrid;
    showPostGrid = function(updateHistory = true) {
        stopReadingTimer();
        originalShowPostGrid(updateHistory);
    };

    // Initialize reading timer if already on single post
    $(document).ready(function() {
        handleReadingTimer();
    });

})(jQuery);