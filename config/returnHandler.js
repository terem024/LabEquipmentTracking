function processRFID(rfidValue) {
    fetch("fetchEquipment.php?rfid=" + encodeURIComponent(rfidValue))
        .then(res => res.json())
        .then(data => {
            if (data.status !== "ok") {
                alert("Equipment not found.");
                return;
            }

            document.getElementById("eqID").textContent = data.equipment_id;
            document.getElementById("eqName").textContent = data.item_name;
            document.getElementById("eqCategory").textContent = data.category;
            document.getElementById("eqQty").textContent = data.quantity;
            document.getElementById("eqRFID").textContent = data.rfid;

            document.getElementById("rfidPopup").style.display = "flex";

            document.getElementById("confirmReturn").onclick = () => {
                confirmReturn(data.rfid);
            };
        });
}

function confirmReturn(rfid) {
    fetch("saveReturn.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "rfid=" + encodeURIComponent(rfid)
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        document.getElementById("rfidPopup").style.display = "none";
    });
}

document.getElementById("cancelReturn").onclick = () => {
    document.getElementById("rfidPopup").style.display = "none";
};
