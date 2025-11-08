function saveCliente(id = null) {
  const form = document.getElementById("formAddCliente");
  const formData = new FormData(form);
  if (id) formData.append("id", id);

  document.getElementById("loadingCliente").style.display = "inline";
  document.getElementById("btnAddCliente").disabled = true;

  fetch("/cliente/salvar", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      document.getElementById("loadingCliente").style.display = "none";
      document.getElementById("btnAddCliente").disabled = false;

      if (data.success) {
        document.getElementById("msg").innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
        setTimeout(() => {
          $("#modaladdcliente").modal("hide");
          location.reload();
        }, 1500);
      } else {
        document.getElementById("msg").innerHTML = `<div class='alert alert-danger'>${data.message}</div>`;
      }
    })
    .catch((err) => {
      document.getElementById("loadingCliente").style.display = "none";
      document.getElementById("btnAddCliente").disabled = false;
      document.getElementById("msg").innerHTML = `<div class='alert alert-danger'>Erro ao salvar cliente.</div>`;
      console.error(err);
    });
}
