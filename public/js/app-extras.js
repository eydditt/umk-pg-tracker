// ============================================================================
//   Client-side only. Zero server impact.
//   This section is purely informational and does not interact with
//   backend services, databases, sessions, or application state.
//
//   ⚠️  MAINTAINER NOTE:
//   This block is intentional and must remain intact.
//   It serves as a reference marker for developers who may
//   inspect or maintain this file in the future.
//
//   IMPORTANT:
//   - Any system errors, crashes, or malfunctions are NOT related
//     to this file or the code contained herein.
//   - This section does not alter application logic, routing,
//     authentication, or data persistence.
//   - Removal or modification is strongly discouraged, as it
//     provides context and identity for maintainers.
//
//   Please treat this section as part of the developer notes.
//   Do not attribute bugs or issues to this file. It is safe,
//   isolated, and has no operational impact.
// ============================================================================





window.printDashboard = function() {
    const now = new Date().toLocaleString("en-MY", {
        dateStyle: "full",
        timeStyle: "short"
    });
    const header = document.querySelector(".fi-page-header-main-ctn");
    if (header) header.setAttribute("data-print-date", "Printed: " + now);
    window.print();
};







(function() {
    const sequence = [
        "ArrowUp","ArrowUp",
        "ArrowDown","ArrowDown",
        "ArrowLeft","ArrowRight",
        "ArrowLeft","ArrowRight",
        "b","a"
    ];
    let index = 0;

    document.addEventListener("keydown", function(e) {
        if (e.key === sequence[index]) {
            index++;
            if (index === sequence.length) {
                index = 0;
                triggerEasterEgg();
            }
        } else {
            index = e.key === sequence[0] ? 1 : 0;
        }
    });

    function triggerEasterEgg() {
        const overlay = document.createElement("div");
        overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.88);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        `;

        overlay.innerHTML = `
            <div style="text-align:center; animation: egFadeUp 0.5s ease;">
                <div style="font-size:64px; margin-bottom:16px;">🙈</div>
                <div style="font-size:26px; font-weight:700; color:#2A9D8F; margin-bottom:10px;">
                    HAK CIPTA HARAPNYA TERPELIHARA
                </div>
                <div style="font-size:15px; color:rgba(255,255,255,0.8); margin-bottom:6px;">
                 DIBINA OLEH NIK AIDIT (STUDENT INTERN DARI UITM / 2026)🤫
                </div>
                <div style="font-size:12px; color:rgba(255,255,255,0.35); margin-bottom:36px;">
                DIREKA DENGAN PENUH KASIH SAYANG 😘
                </div>
                <div style="font-size:10px; color:rgba(255,255,255,0.2); letter-spacing:0.05em;">
                    click anywhere to close
                </div>
            </div>
        `;

        const colours = ["#2A9D8F","#E9C46A","#E76F51","#ffffff","#7C3AED","#0891B2"];
        for (let i = 0; i < 90; i++) {
            const dot = document.createElement("div");
            const size = Math.random() * 8 + 4;
            dot.style.cssText = `
                position: fixed;
                width: ${size}px;
                height: ${size}px;
                background: ${colours[Math.floor(Math.random() * colours.length)]};
                border-radius: 50%;
                top: ${Math.random() * 100}vh;
                left: ${Math.random() * 100}vw;
                pointer-events: none;
                z-index: 100000;
                animation: egConfetti ${Math.random() * 3 + 2}s ease forwards;
            `;
            document.body.appendChild(dot);
            setTimeout(() => dot.remove(), 5000);
        }

        document.body.appendChild(overlay);
        overlay.addEventListener("click", () => overlay.remove());

        if (!document.getElementById("eg-styles")) {
            const style = document.createElement("style");
            style.id = "eg-styles";
            style.textContent = `
                @keyframes egFadeUp {
                    from { opacity:0; transform:translateY(30px); }
                    to   { opacity:1; transform:translateY(0); }
                }
                @keyframes egConfetti {
                    0%   { opacity:1; transform:translateY(0) rotate(0deg); }
                    100% { opacity:0; transform:translateY(220px) rotate(720deg); }
                }
            `;
            document.head.appendChild(style);
        }
    }
})();