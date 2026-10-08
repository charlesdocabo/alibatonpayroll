@extends('layouts.app')

@section('title', 'HR Analytics & AI Advisor - Alibaton Construction Inc.')

@section('styles')
<style>
    /* =========================
       PAGE HEADER
    ========================= */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #111111;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #666666;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-ai-action {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #111111;
        background: #111111;
        color: #ffffff;
    }

    .btn-ai-action:hover {
        background: #282828;
        transform: translateY(-1px);
    }

    .btn-ai-action.gold {
        background: #F4C400;
        color: #111111;
        border-color: #d8ad00;
    }

    .btn-ai-action.gold:hover {
        background: #ffd31a;
    }

    .btn-ai-action.outline {
        background: #ffffff;
        color: #111111;
        border-color: #d1d5db;
    }

    .btn-ai-action.outline:hover {
        background: #f8fafc;
        border-color: #9ca3af;
    }

    /* =========================
       AI HERO BANNER
    ========================= */
    .gemini-hero-card {
        background: linear-gradient(135deg, #111111 0%, #1f1f1f 60%, #2a240d 100%);
        border: 1px solid #333333;
        border-left: 5px solid #F4C400;
        border-radius: 14px;
        padding: 22px 26px;
        color: #ffffff;
        margin-bottom: 25px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .gemini-hero-content {
        max-width: 780px;
    }

    .gemini-badge-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .gemini-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .gemini-pill.primary {
        background: #F4C400;
        color: #111111;
    }

    .gemini-pill.model {
        background: rgba(255, 255, 255, 0.12);
        color: #e2e8f0;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .gemini-pill.status {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.4);
    }

    .gemini-hero-title {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
    }

    .gemini-hero-desc {
        margin: 0;
        color: #cbd5e1;
        font-size: 13px;
        line-height: 1.5;
    }

    /* =========================
       ANALYTICS KPI CARDS
    ========================= */
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .analytics-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border-top: 4px solid #F4C400;
        transition: 0.2s ease;
    }

    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    .analytics-card.dark {
        border-top-color: #111111;
    }

    .analytics-label {
        color: #777777;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .analytics-value {
        color: #111111;
        font-size: 26px;
        font-weight: 800;
    }

    .analytics-description {
        margin-top: 6px;
        color: #888888;
        font-size: 12px;
    }

    /* =========================
       EXECUTIVE AI INSIGHTS PANEL
    ========================= */
    .ai-insights-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
        position: relative;
    }

    .ai-insights-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .ai-insights-title {
        margin: 0;
        font-size: 19px;
        font-weight: 800;
        color: #111111;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-meta-info {
        font-size: 12px;
        color: #64748b;
    }

    .ai-report-body {
        font-size: 14px;
        line-height: 1.7;
        color: #334155;
    }

    .ai-report-body h3 {
        color: #0f172a;
        margin: 18px 0 8px;
        font-size: 16px;
        font-weight: 700;
    }

    .ai-report-body strong {
        color: #0f172a;
    }

    .ai-report-body ul {
        margin: 8px 0 16px 20px;
        padding: 0;
    }

    .ai-report-body li {
        margin-bottom: 6px;
    }

    /* =========================
       ASK GEMINI INTERACTIVE CONSOLE
    ========================= */
    .gemini-console-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .gemini-console-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .gemini-console-title {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #111111;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-prompts-bar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .quick-prompt-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quick-prompt-btn:hover {
        background: #F4C400;
        color: #111111;
        border-color: #d8ad00;
        transform: translateY(-1px);
    }

    .chat-container {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        padding: 16px;
        max-height: 420px;
        min-height: 180px;
        overflow-y: auto;
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .chat-message {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        max-width: 90%;
    }

    .chat-message.user {
        margin-left: auto;
        flex-direction: row-reverse;
    }

    .chat-avatar {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .chat-avatar.ai {
        background: #111111;
        color: #F4C400;
    }

    .chat-avatar.user {
        background: #3b82f6;
        color: #ffffff;
    }

    .chat-bubble {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.6;
    }

    .chat-message.ai .chat-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    .chat-message.user .chat-bubble {
        background: #111111;
        color: #ffffff;
    }

    .chat-input-wrapper {
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }

    .chat-input:focus {
        border-color: #111111;
        box-shadow: 0 0 0 3px rgba(244, 196, 0, 0.3);
    }

    .chat-send-btn {
        background: #111111;
        color: #F4C400;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }

    .chat-send-btn:hover {
        background: #252525;
    }

    .chat-send-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* =========================
       DASHBOARD PANELS
    ========================= */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .panel-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111111;
    }

    .panel-subtitle {
        margin: 4px 0 20px;
        color: #777777;
        font-size: 13px;
    }

    .status-row {
        margin-bottom: 18px;
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 7px;
    }

    .status-name {
        font-size: 13px;
        font-weight: 700;
        color: #333333;
    }

    .status-count {
        font-size: 13px;
        font-weight: 700;
        color: #111111;
    }

    .progress-bar {
        width: 100%;
        height: 10px;
        background: #eeeeee;
        border-radius: 20px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 20px;
        background: #F4C400;
    }

    .progress-fill.inactive {
        background: #111111;
    }

    .status-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 25px;
    }

    .status-box {
        padding: 15px;
        border-radius: 9px;
        background: #f7f7f7;
        border-left: 4px solid #F4C400;
    }

    .status-box.dark {
        border-left-color: #111111;
    }

    .status-box-label {
        font-size: 12px;
        color: #777777;
        margin-bottom: 5px;
    }

    .status-box-value {
        font-size: 21px;
        font-weight: 800;
        color: #111111;
    }

    .payroll-main {
        font-size: 30px;
        font-weight: 800;
        color: #111111;
        margin-bottom: 5px;
    }

    .payroll-label {
        color: #777777;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .payroll-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .payroll-stat {
        background: #f7f7f7;
        border-radius: 9px;
        padding: 15px;
    }

    .payroll-stat-label {
        color: #777777;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .payroll-stat-value {
        color: #111111;
        font-size: 20px;
        font-weight: 800;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .service-card {
        background: #f7f7f7;
        border: 1px solid #eeeeee;
        border-radius: 10px;
        padding: 18px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .service-card:hover {
        border-color: #F4C400;
        background: #fffdf0;
        transform: translateY(-2px);
    }

    .service-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F4C400;
        color: #111111;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .service-name {
        color: #111111;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .service-count {
        color: #777777;
        font-size: 12px;
    }

    /* =========================
       CONFIG MODAL
    ========================= */
    #ai-config-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    #ai-config-modal.show {
        display: flex;
    }

    .modal-box {
        background: #ffffff;
        border-radius: 14px;
        padding: 30px;
        max-width: 520px;
        width: 90%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .modal-title {
        margin: 0;
        font-size: 19px;
        font-weight: 800;
        color: #111111;
    }

    .close-modal-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #64748b;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        box-sizing: border-box;
    }

    .form-help {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 5px;
        line-height: 1.4;
    }

    @media (max-width: 1050px) {
        .analytics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 800px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .service-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

{{-- =========================
     PAGE HEADER
========================= --}}
<div class="page-header">

    <div>
        <h1 class="page-title">
            <span>HR Analytics &amp; Workforce AI</span>
        </h1>

        <p class="page-subtitle">
            Integrated Payroll, Benefits Management &amp; Executive Intelligence powered by <strong>Google Gemini API</strong> for Alibaton Construction Inc.
        </p>
    </div>

    <div class="header-actions">
        <button type="button" class="btn-ai-action gold" onclick="focusGeminiChat()">
            ✨ Ask Gemini Advisor
        </button>

        <button type="button" class="btn-ai-action outline" id="btnRefreshAi" onclick="refreshAiReport()">
            🔄 Refresh AI Brief
        </button>

        <button type="button" class="btn-ai-action outline" onclick="openConfigModal()">
            ⚙️ AI Config
        </button>

        <button type="button" class="btn-ai-action outline" onclick="window.print()">
            🖨 Print / PDF
        </button>
    </div>

</div>

{{-- =========================
     GEMINI 2.5 FLASH HERO BANNER
========================= --}}
<div class="gemini-hero-card">
    <div class="gemini-hero-content">
        <div class="gemini-badge-row">
            <span class="gemini-pill primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                Google Gemini API Core
            </span>
            <span class="gemini-pill model">
                Model: {{ $aiExecutiveBrief['model'] ?? 'gemini-2.5-flash' }}
            </span>
            <span class="gemini-pill status">
                ● Live Database Synthesis Active
            </span>
        </div>

        <h2 class="gemini-hero-title">
            Executive Decision Intelligence &amp; Predictive HR Advisor
        </h2>

        <p class="gemini-hero-desc">
            Automated analysis evaluating workforce productivity ratios, payroll budget trajectories, employee benefits utilization, and claims turnaround times for Alibaton Construction Inc.
        </p>
    </div>

    <div>
        <button type="button" class="btn-ai-action gold" onclick="focusGeminiChat()" style="white-space:nowrap;">
            Start AI Dialogue ➔
        </button>
    </div>
</div>

@if(isset($error))
    <div class="alert alert-error">
        {{ $error }}
    </div>
@endif

{{-- =========================
     MAIN KPI CARDS
========================= --}}
<div class="analytics-grid">

    {{-- TOTAL EMPLOYEES --}}
    <div class="analytics-card">
        <div class="analytics-label">
            Total Employees
        </div>
        <div class="analytics-value">
            {{ $totalEmployees ?? 0 }}
        </div>
        <div class="analytics-description">
            Total registered workforce roster
        </div>
    </div>

    {{-- ACTIVE EMPLOYEES --}}
    <div class="analytics-card">
        <div class="analytics-label">
            Active Personnel
        </div>
        <div class="analytics-value">
            {{ $activeEmployees ?? 0 }}
        </div>
        <div class="analytics-description">
            {{ number_format($activePercentage ?? 0, 1) }}% active duty ratio
        </div>
    </div>

    {{-- TOTAL PAYROLL (CONFIDENTIAL MASKED) --}}
    <div class="analytics-card dark">
        <div class="analytics-label">
            Total Gross Payroll
        </div>
        <div class="analytics-value confidential-amount">
            ₱{{ number_format($totalPayroll ?? 0, 2) }}
        </div>
        <div class="analytics-description">
            Recorded enterprise payroll expenditure
        </div>
    </div>

    {{-- AVERAGE SALARY (CONFIDENTIAL MASKED) --}}
    <div class="analytics-card dark">
        <div class="analytics-label">
            Average Base Salary
        </div>
        <div class="analytics-value confidential-amount">
            ₱{{ number_format($averageSalary ?? 0, 2) }}
        </div>
        <div class="analytics-description">
            Monthly average compensation band
        </div>
    </div>

</div>

{{-- ========================================================
     EXECUTIVE AI STRATEGIC REPORT (POWERED BY GEMINI 2.5 FLASH)
     ======================================================== --}}
<div class="ai-insights-panel" id="aiReportCard">

    <div class="ai-insights-header">
        <h2 class="ai-insights-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="#F4C400" stroke="#111" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            Executive HR Strategic Briefing
            <span style="font-size:12px;font-weight:600;background:#fef08a;color:#854d0e;padding:2px 8px;border-radius:12px;">Gemini 2.5 Flash</span>
        </h2>

        <div style="display:flex;align-items:center;gap:12px;">
            <span class="ai-meta-info" id="aiReportTimestamp">
                Generated: {{ $aiExecutiveBrief['generated_at'] ?? now()->format('M d, Y h:i A') }}
            </span>
            <button type="button" class="btn-ai-action outline" style="padding:6px 12px;font-size:12px;" onclick="copyAiReport()">
                📋 Copy Brief
            </button>
        </div>
    </div>

    <div class="ai-report-body" id="aiReportContent">
        {!! nl2br(e($aiExecutiveBrief['report'] ?? 'Generating executive insights...')) !!}
    </div>

</div>

{{-- ========================================================
     INTERACTIVE "ASK GEMINI" HR CONSULTANT CONSOLE
     ======================================================== --}}
<div class="gemini-console-card" id="geminiConsole">

    <div class="gemini-console-header">
        <h2 class="gemini-console-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Ask Google Gemini HR Advisor
            <span style="font-size:12px;color:#64748b;font-weight:500;">(Real-time inquiry on company workforce, budget, and labor law compliance)</span>
        </h2>
    </div>

    {{-- Quick Prompt Chips --}}
    <div class="quick-prompts-bar">
        <button type="button" class="quick-prompt-btn" onclick="sendQuickPrompt('What is our projected annual payroll run-rate based on current metrics?')">
            💰 Projected Annual Payroll
        </button>
        <button type="button" class="quick-prompt-btn" onclick="sendQuickPrompt('Analyze workforce retention and risks associated with inactive personnel.')">
            👥 Workforce Retention Analysis
        </button>
        <button type="button" class="quick-prompt-btn" onclick="sendQuickPrompt('Evaluate claims reimbursement turnaround speed and recommend approval SLA improvements.')">
            ⚡ Claims Approval Optimization
        </button>
        <button type="button" class="quick-prompt-btn" onclick="sendQuickPrompt('Draft a concise executive HR summary for the upcoming Board of Directors meeting.')">
            📑 Board of Directors Summary
        </button>
    </div>

    {{-- Chat Thread --}}
    <div class="chat-container" id="chatThread">
        <div class="chat-message ai">
            <div class="chat-avatar ai">AI</div>
            <div class="chat-bubble">
                Hello! I am your <strong>Google Gemini 2.5 Flash HR Analytics Advisor</strong> for Alibaton Construction Inc. I have synchronized with the live workforce roster, payroll entries, benefit packages, and claims records. Ask me any strategic, financial, or workforce question!
            </div>
        </div>
    </div>

    {{-- Chat Input Form --}}
    <form id="geminiChatForm" onsubmit="handleChatSubmit(event)">
        <div class="chat-input-wrapper">
            <input
                type="text"
                id="chatInput"
                class="chat-input"
                placeholder="Ask Gemini about payroll budgets, employee retention, claims trends, labor regulations..."
                autocomplete="off"
                required
            />
            <button type="submit" id="chatSendBtn" class="chat-send-btn">
                <span>Send</span>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>
    </form>

</div>

{{-- =========================
     WORKFORCE & PAYROLL PANELS
========================= --}}
<div class="dashboard-grid">

    {{-- WORKFORCE STATUS --}}
    <div class="panel">
        <h2 class="panel-title">
            Workforce Status Distribution
        </h2>
        <p class="panel-subtitle">
            Active field personnel vs inactive roster headcount.
        </p>

        {{-- ACTIVE --}}
        <div class="status-row">
            <div class="status-header">
                <span class="status-name">Active Employees</span>
                <span class="status-count">
                    {{ $activeEmployees ?? 0 }} ({{ number_format($activePercentage ?? 0, 1) }}%)
                </span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, max(0, $activePercentage ?? 0)) }}%;"></div>
            </div>
        </div>

        {{-- INACTIVE --}}
        <div class="status-row">
            <div class="status-header">
                <span class="status-name">Inactive / On-Leave</span>
                <span class="status-count">
                    {{ $inactiveEmployees ?? 0 }} ({{ number_format($inactivePercentage ?? 0, 1) }}%)
                </span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill inactive" style="width: {{ min(100, max(0, $inactivePercentage ?? 0)) }}%;"></div>
            </div>
        </div>

        <div class="status-summary">
            <div class="status-box">
                <div class="status-box-label">Active Deployable</div>
                <div class="status-box-value">{{ $activeEmployees ?? 0 }}</div>
            </div>
            <div class="status-box dark">
                <div class="status-box-label">Inactive Roster</div>
                <div class="status-box-value">{{ $inactiveEmployees ?? 0 }}</div>
            </div>
        </div>
    </div>

    {{-- PAYROLL OVERVIEW --}}
    <div class="panel">
        <h2 class="panel-title">
            Payroll Overview
        </h2>
        <p class="panel-subtitle">
            Current recorded disbursements and employee base rates.
        </p>

        <div class="payroll-main confidential-amount">
            ₱{{ number_format($totalPayroll ?? 0, 2) }}
        </div>
        <div class="payroll-label">
            Total recorded gross payroll
        </div>

        <div class="payroll-stats">
            <div class="payroll-stat">
                <div class="payroll-stat-label">Payroll Records</div>
                <div class="payroll-stat-value">{{ $payrollRecords ?? 0 }}</div>
            </div>
            <div class="payroll-stat">
                <div class="payroll-stat-label">Average Monthly Salary</div>
                <div class="payroll-stat-value confidential-amount">₱{{ number_format($averageSalary ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

</div>

{{-- =========================
     HR SERVICES MODULES
========================= --}}
<div class="panel" style="margin-bottom:25px;">
    <h2 class="panel-title">
        Enterprise Service Modules
    </h2>
    <p class="panel-subtitle">
        Microservice records integrated into this analytics engine.
    </p>

    <div class="service-grid">
        <a href="{{ url('/benefits') }}" class="service-card">
            <div class="service-icon">B</div>
            <div class="service-name">Benefits &amp; Contributions</div>
            <div class="service-count">{{ $totalBenefits ?? 0 }} recorded packages</div>
        </a>

        <a href="{{ url('/claims') }}" class="service-card">
            <div class="service-icon">C</div>
            <div class="service-name">Claims &amp; Reimbursements</div>
            <div class="service-count">{{ $totalClaims ?? 0 }} total filed</div>
        </a>

        <a href="{{ url('/payrolls') }}" class="service-card">
            <div class="service-icon">₱</div>
            <div class="service-name">Payroll &amp; Payslips</div>
            <div class="service-count">{{ $payrollRecords ?? 0 }} payroll sheets</div>
        </a>
    </div>
</div>

{{-- ========================================================
     AI MODEL CONFIGURATION MODAL
     ======================================================== --}}
<div id="ai-config-modal" role="dialog" aria-modal="true">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title">⚙️ Google Gemini AI Configuration</h3>
            <button type="button" class="close-modal-btn" onclick="closeConfigModal()">&times;</button>
        </div>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;margin-bottom:18px;font-size:13px;">
            <div style="margin-bottom:6px;"><strong>Active Model:</strong> <code>Google Gemini 2.5 Flash</code></div>
            <div style="margin-bottom:6px;"><strong>Active Gateway:</strong> {{ $aiProviderInfo['provider'] ?? 'OpenRouter / Google' }}</div>
            <div><strong>Configured Key:</strong> <code>{{ $aiProviderInfo['key_preview'] ?? 'Loaded' }}</code></div>
        </div>

        <form id="keyConfigForm" onsubmit="handleKeyConfig(event)">
            @csrf
            <div class="form-group">
                <label class="form-label" for="apiKeyInput">Update API Key (Google AI Studio or OpenRouter):</label>
                <input type="text" id="apiKeyInput" class="form-input" placeholder="AIzaSy... or sk-or-v1-..." required />
                <div class="form-help">
                    Supports Google AI Studio Gemini API keys (starts with <code>AIzaSy...</code>) or OpenRouter API keys (starts with <code>sk-or-v1-...</code>).
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <button type="button" class="btn-ai-action outline" onclick="closeConfigModal()">Cancel</button>
                <button type="submit" class="btn-ai-action gold" id="saveKeyBtn">Save &amp; Reload Engine</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================
     CLIENT-SIDE JAVASCRIPT: GEMINI CHAT, REFRESH, MARKDOWN
     ======================================================== --}}
<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
let chatHistory = [];

function focusGeminiChat() {
    const el = document.getElementById('geminiConsole');
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => document.getElementById('chatInput').focus(), 400);
    }
}

function openConfigModal() {
    document.getElementById('ai-config-modal').classList.add('show');
}

function closeConfigModal() {
    document.getElementById('ai-config-modal').classList.remove('show');
}

function sendQuickPrompt(promptText) {
    document.getElementById('chatInput').value = promptText;
    document.getElementById('geminiChatForm').dispatchEvent(new Event('submit'));
}

function formatMarkdown(text) {
    if (!text) return '';
    let formatted = text
        .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
        .replace(/^### (.*$)/gim, '<h4 style="margin:10px 0 4px;color:#0f172a;font-size:15px;font-weight:700;">$1</h4>')
        .replace(/^## (.*$)/gim, '<h3 style="margin:12px 0 6px;color:#0f172a;font-size:16px;font-weight:700;">$1</h3>')
        .replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/gim, '<em>$1</em>')
        .replace(/^\s*[\u2022\-\*]\s+(.*$)/gim, '<li style="margin-left:18px;margin-bottom:4px;">$1</li>')
        .replace(/\n\n/g, '<br><br>')
        .replace(/\n/g, '<br>');
    return formatted;
}

// Format the initial server-rendered executive report cleanly on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    const reportEl = document.getElementById('aiReportContent');
    if (reportEl && reportEl.textContent.trim()) {
        reportEl.innerHTML = formatMarkdown(reportEl.textContent.trim());
    }
});

async function handleChatSubmit(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const sendBtn = document.getElementById('chatSendBtn');
    const question = input.value.trim();
    if (!question) return;

    input.value = '';
    sendBtn.disabled = true;

    const chatThread = document.getElementById('chatThread');

    // Append User Message
    const userMsg = document.createElement('div');
    userMsg.className = 'chat-message user';
    userMsg.innerHTML = `<div class="chat-avatar user">You</div><div class="chat-bubble">${escapeHtml(question)}</div>`;
    chatThread.appendChild(userMsg);

    // Append AI Loading Message
    const aiMsg = document.createElement('div');
    aiMsg.className = 'chat-message ai';
    aiMsg.innerHTML = `<div class="chat-avatar ai">AI</div><div class="chat-bubble" id="tempLoading"><span style="color:#64748b;">⚡ Google Gemini 2.5 Flash is analyzing live workforce data...</span></div>`;
    chatThread.appendChild(aiMsg);
    chatThread.scrollTop = chatThread.scrollHeight;

    try {
        const res = await fetch('{{ route("analytics.gemini.ask") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                question: question,
                history: chatHistory.slice(-6)
            })
        });

        const data = await res.json();
        const bubble = aiMsg.querySelector('.chat-bubble');

        if (data.success && data.answer) {
            bubble.innerHTML = formatMarkdown(data.answer);
            chatHistory.push({ role: 'user', content: question });
            chatHistory.push({ role: 'assistant', content: data.answer });
        } else {
            bubble.innerHTML = '<span style="color:#e53e3e;">Unable to generate response. Please check API configuration.</span>';
        }
    } catch (err) {
        aiMsg.querySelector('.chat-bubble').innerHTML = '<span style="color:#e53e3e;">Network error connecting to Gemini advisor.</span>';
    } finally {
        sendBtn.disabled = false;
        chatThread.scrollTop = chatThread.scrollHeight;
    }
}

async function refreshAiReport() {
    const btn = document.getElementById('btnRefreshAi');
    const content = document.getElementById('aiReportContent');
    const timestamp = document.getElementById('aiReportTimestamp');

    btn.disabled = true;
    btn.innerHTML = '⏳ Generating...';
    content.innerHTML = '<div style="padding:20px;text-align:center;color:#64748b;">⚡ Running deep Gemini 2.5 Flash synthesis over payroll, benefits, and workforce records...</div>';

    try {
        const res = await fetch('{{ route("analytics.gemini.refresh") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        if (data.success && data.brief) {
            content.innerHTML = formatMarkdown(data.brief.report);
            timestamp.textContent = 'Generated: ' + data.brief.generated_at;
        } else {
            content.innerHTML = '<span style="color:#e53e3e;">Could not refresh report.</span>';
        }
    } catch (err) {
        content.innerHTML = '<span style="color:#e53e3e;">Connection error during report refresh.</span>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '🔄 Refresh AI Brief';
    }
}

async function handleKeyConfig(e) {
    e.preventDefault();
    const key = document.getElementById('apiKeyInput').value.trim();
    const btn = document.getElementById('saveKeyBtn');
    if (!key) return;

    btn.disabled = true;
    btn.textContent = 'Saving...';

    const type = key.startsWith('AIzaSy') ? 'gemini' : 'openrouter';

    try {
        const res = await fetch('{{ route("analytics.gemini.configure-key") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ api_key: key, type: type })
        });

        const data = await res.json();
        if (data.success) {
            alert('Success: ' + data.message);
            closeConfigModal();
            refreshAiReport();
        } else {
            alert('Error updating key.');
        }
    } catch (err) {
        alert('Network error while saving API key.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save & Reload Engine';
    }
}

function copyAiReport() {
    const text = document.getElementById('aiReportContent').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Executive AI Briefing copied to clipboard!');
    });
}

function escapeHtml(string) {
    const entityMap = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
    return String(string).replace(/[&<>"']/g, s => entityMap[s]);
}
</script>

@endsection
