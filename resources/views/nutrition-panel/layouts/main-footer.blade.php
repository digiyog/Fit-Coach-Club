<style>
    .fcc-modern-footer-container {
        padding: 0 16px 28px 16px;
        margin-top: 10px;
    }

    .fcc-modern-footer {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 16px 24px;
        max-width: 1560px;
        margin: 0 auto;
        font-family: 'Outfit', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #64748b;
        font-size: 12.5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 2px 12px -2px rgba(15, 23, 42, 0.04);
    }

    .fcc-footer-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .fcc-footer-brand-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 13px;
        letter-spacing: -0.01em;
    }

    .fcc-footer-dot-sep {
        color: #cbd5e1;
    }

    .fcc-footer-copy-text {
        color: #64748b;
        font-weight: 500;
    }

    .fcc-footer-right {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .fcc-footer-status-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 3px 9px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
    }

    .fcc-footer-live-dot {
        width: 6.5px;
        height: 6.5px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
        display: inline-block;
    }

    .fcc-footer-made-with {
        color: #64748b;
        font-size: 12.5px;
        font-weight: 500;
    }

    .fcc-footer-heart-anim {
        color: #ef4444 !important;
        font-family: 'FontAwesome' !important;
        display: inline-block;
        animation: fccPulseHeart 1.8s ease infinite;
        margin: 0 2px;
        font-size: 12px;
    }

    @keyframes fccPulseHeart {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.25); }
    }

    .fcc-footer-brand-link {
        color: #3b46f1;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .fcc-footer-brand-link:hover {
        color: #1d28cd;
        text-decoration: underline;
    }
</style>

<div class="fcc-modern-footer-container">
    <footer class="fcc-modern-footer">
        <div class="fcc-footer-left">
            <span class="fcc-footer-brand-title">Fit Coach Club</span>
            <span class="fcc-footer-dot-sep">·</span>
            <span class="fcc-footer-copy-text">Copyright &copy; {{ date("Y") }} All rights reserved.</span>
        </div>

        <div class="fcc-footer-right">
            <span class="fcc-footer-status-tag">
                <span class="fcc-footer-live-dot"></span>
                <span>Cloud Active</span>
            </span>
            <span class="fcc-footer-made-with">
                Made in India with <i class="fa fa-heart fcc-footer-heart-anim"></i> by 
                <a href="https://digiyog.com" target="_blank" class="fcc-footer-brand-link">Digiyog Technosoft</a>
            </span>
        </div>
    </footer>
</div>