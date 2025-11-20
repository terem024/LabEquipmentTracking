function simulateRFID(tag) {
    processRFID(tag);
}

function processRFID(rfidValue) {
    if (!rfidValue) return;

    fetch("fetchEquipment.php?rfid=" + encodeURIComponent(rfidValue))
        .then(res => res.json())
        .then(data => {
            if (data.status === "ok") {

                document.getElementById("eqID").textContent = data.equipment_id;
                document.getElementById("eqName").textContent = data.item_name;
                document.getElementById("eqCategory").textContent = data.category;
                document.getElementById("eqQty").textContent = data.quantity;
                document.getElementById("eqRFID").textContent = data.rfid;

                document.getElementById("rfidPopup").style.display = "flex";

                document.getElementById("confirmBorrow").onclick = () => {
                    confirmBorrow(data.rfid);
                };

            } else {
                alert("Equipment not found.");
            }
        })
        .catch(err => console.error(err));
}

document.getElementById("cancelBorrow").onclick = () => {
    document.getElementById("rfidPopup").style.display = "none";
};

function confirmBorrow(rfid) {
    fetch("saveBorrow.php", {
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
