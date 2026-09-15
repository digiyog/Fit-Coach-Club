<style>
    .fcc-footer {
        background: #ffffff;
        border-top: 1px solid #edf2f7;
        padding: 16px 28px;
        margin-top: 32px;
        font-family: 'Outfit', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #64748b;
        font-size: 12.5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 -2px 10px rgba(15, 23, 42, 0.02);
    }

    .fcc-footer-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .fcc-footer-brand {
        font-weight: 700;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .fcc-footer-dot {
        color: #cbd5e1;
    }

    .fcc-footer-right {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .fcc-footer-heart {
        color: #ef4444 !important;
        font-family: 'FontAwesome' !important;
        display: inline-block;
        animation: fccHeartPulse 1.8s ease infinite;
        margin: 0 2px;
        font-size: 12px;
    }

    @keyframes fccHeartPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.22); }
    }

    .fcc-footer-link {
        color: #3b46f1;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .fcc-footer-link:hover {
        color: #1d28cd;
        text-decoration: underline;
    }

    .fcc-footer-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #475569;
    }

    .fcc-footer-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
        display: inline-block;
    }
</style>

<footer class="fcc-footer">
    <div class="fcc-footer-left">
        <span class="fcc-footer-brand">Fit Coach Club</span>
        <span class="fcc-footer-dot">·</span>
        <span>Copyright &copy; {{ date("Y") }} All rights reserved.</span>
    </div>

    <div class="fcc-footer-right">
        <span class="fcc-footer-pill">
            <span class="fcc-footer-status-dot"></span>
            <span>Cloud Active</span>
        </span>
        <span>Made in India with <i class="fa fa-heart fcc-footer-heart"></i> by <a href="https://digiyog.com" target="_blank" class="fcc-footer-link">Digiyog Technosoft</a></span>
    </div>
</footer>