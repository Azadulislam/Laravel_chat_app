<div id="toastContainer"></div>
<div id="pinsContainer" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:9997;pointer-events:none;"></div>

<div id="contextMenu">
    <button id="addCommentBtn">💬 Add Comment</button>
</div>

<div id="commentModal" style="position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);width:90%;max-width:400px;padding:24px;">
        <h3 id="modalTitle" style="margin:0 0 16px 0;font-size:18px;color: #333">Add Comment</h3>
        <textarea id="commentTextarea" style="color:#333;width:100%;border:1px solid #d1d5db;border-radius:8px;padding:12px;min-height:100px;resize:vertical;box-sizing:border-box;" placeholder="Enter your comment..."></textarea>
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:16px;">
            <button id="cancelBtn" style="padding:10px 20px;border:none;border-radius:6px;cursor:pointer;background:#f3f4f6;color:#374151;">Cancel</button>
            <button id="submitBtn" style="padding:10px 20px;border:none;border-radius:6px;cursor:pointer;background:#3b82f6;color:white;">Submit</button>
        </div>
    </div>
</div>
