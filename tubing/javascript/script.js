// Data schedules akan diisi oleh PHP melalui dashboard_admin.php
var schedules = [];

// ===== FUNGSI UMUM =====
function showPage(pageName) {
  var pages = document.querySelectorAll(".page");
  for (var i = 0; i < pages.length; i++) {
    pages[i].classList.remove("active");
  }

  document.getElementById(pageName).classList.add("active");

  var menuItems = document.querySelectorAll(".menu-item");
  for (var j = 0; j < menuItems.length; j++) {
    menuItems[j].classList.remove("active");
    if (menuItems[j].getAttribute("data-page") === pageName) {
      menuItems[j].classList.add("active");
    }
  }

  var titles = {
    dashboard: "Dashboard",
    schedule: "Kelola Jadwal",
    gallery: "Kelola Galeri",
  };
  document.getElementById("pageTitle").textContent = titles[pageName];
}

function renderSchedules() {
  var container = document.getElementById("upcomingSchedules");
  if (!container) {
    console.log("Element upcomingSchedules tidak ditemukan");
    return;
  }

  container.innerHTML = "";
  console.log("Jumlah data schedules:", schedules.length);

  // Ambil hanya 3 jadwal terdekat untuk dashboard
  var today = new Date();
  today.setHours(0, 0, 0, 0); // Reset waktu ke 00:00:00 untuk perbandingan tanggal saja

  var upcomingSchedules = schedules
    .filter(function (schedule) {
      try {
        var scheduleDate = new Date(schedule.date);
        scheduleDate.setHours(0, 0, 0, 0);
        console.log(
          "Compare:",
          scheduleDate,
          ">=",
          today,
          "=",
          scheduleDate >= today
        );
        return scheduleDate >= today;
      } catch (error) {
        console.error("Error parsing date:", schedule.date, error);
        return false;
      }
    })
    .sort(function (a, b) {
      return new Date(a.date) - new Date(b.date);
    })
    .slice(0, 3);

  console.log("Upcoming schedules:", upcomingSchedules);

  if (upcomingSchedules.length === 0) {
    container.innerHTML =
      '<div class="empty-state"><p>Tidak ada jadwal yang akan datang</p></div>';
    return;
  }

  for (var i = 0; i < upcomingSchedules.length; i++) {
    var schedule = upcomingSchedules[i];
    var scheduleItem = document.createElement("div");
    scheduleItem.className = "schedule-item";

    // Format tanggal yang lebih baik
    var formattedDate = formatDate(schedule.date);

    scheduleItem.innerHTML =
      '<div class="schedule-header">' +
      "<div>" +
      '<div class="customer-name">👤 ' +
      (schedule.customer || "N/A") +
      "</div>" +
      '<div class="service-type">' +
      (schedule.service || "River Tubing") +
      "</div>" +
      '<div class="location">📍 ' +
      (schedule.location || "N/A") +
      "</div>" +
      "</div>" +
      '<div class="schedule-date">' +
      '<div class="date-badge">' +
      formattedDate +
      "</div>" +
      "</div>" +
      "</div>";
    container.appendChild(scheduleItem);
  }
}

// Fungsi helper untuk format tanggal
function formatDate(dateString) {
  try {
    var date = new Date(dateString);
    var options = { day: "numeric", month: "long", year: "numeric" };
    return date.toLocaleDateString("id-ID", options);
  } catch (error) {
    return dateString;
  }
}

// ===== FUNGSI ADMIN (CRUD OPERATIONS) =====
function toggleForm() {
  var form = document.getElementById("formJadwal");
  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    resetForm();
  } else {
    form.style.display = "none";
  }
}

function resetForm() {
  document.getElementById("formTitle").innerText = "Tambah Jadwal Baru";
  document.getElementById("inputAksi").value = "tambah";
  document.getElementById("inputId").value = "";
  document.getElementById("inputNama").value = "";
  document.getElementById("inputHp").value = "";
  document.getElementById("inputTanggal").value = "";
  document.getElementById("inputLokasi").value = "";
  document.getElementById("inputStatus").value = "pending";
  document.getElementById("inputNotes").value = "";
}

function editData(data) {
  var form = document.getElementById("formJadwal");
  form.style.display = "block";

  document.getElementById("formTitle").innerText = "Edit Jadwal";
  document.getElementById("inputAksi").value = "edit";
  document.getElementById("inputId").value = data.id;

  document.getElementById("inputNama").value = data.customer_name;
  document.getElementById("inputHp").value = data.customer_phone;
  document.getElementById("inputTanggal").value = data.schedule_date;
  document.getElementById("inputLokasi").value = data.location;
  document.getElementById("inputStatus").value = data.status;
  document.getElementById("inputNotes").value = data.notes;

  // Scroll ke form
  form.scrollIntoView({ behavior: "smooth" });
}

function tambahJadwal() {
  showPage("schedule");
  toggleForm();
}

function validateForm() {
  const nama = document.getElementById("inputNama").value.trim();
  const tanggal = document.getElementById("inputTanggal").value;
  const lokasi = document.getElementById("inputLokasi").value.trim();

  if (!nama) {
    alert("Nama customer harus diisi");
    return false;
  }

  if (!tanggal) {
    alert("Tanggal harus diisi");
    return false;
  }

  if (!lokasi) {
    alert("Lokasi harus diisi");
    return false;
  }

  return true;
}

// ===== EVENT LISTENERS =====
document.addEventListener("DOMContentLoaded", function () {
  renderSchedules();

  // Navigasi menu
  var menuItems = document.querySelectorAll(".menu-item");
  for (var i = 0; i < menuItems.length; i++) {
    menuItems[i].addEventListener("click", function () {
      var page = this.getAttribute("data-page");
      showPage(page);
    });
  }

  // Quick actions
  var actionCards = document.querySelectorAll(".action-card");
  for (var j = 0; j < actionCards.length; j++) {
    actionCards[j].addEventListener("click", function () {
      var page = this.getAttribute("data-page");
      showPage(page);
    });
  }

  // View all link
  var viewAllLink = document.querySelector(".view-all");
  if (viewAllLink) {
    viewAllLink.addEventListener("click", function (e) {
      e.preventDefault();
      var page = this.getAttribute("data-page");
      showPage(page);
    });
  }

  // Konfirmasi hapus
  const deleteButtons = document.querySelectorAll(".btn-delete");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      if (!confirm("Yakin hapus data ini?")) {
        e.preventDefault();
      }
    });
  });

  // Validasi form
  const form = document.querySelector("#formJadwal form");
  if (form) {
    form.addEventListener("submit", function (e) {
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  }
});
