// Interacoes minimas, sem dependencias.
document.addEventListener('DOMContentLoaded', function () {
  // Confirmacao de exclusao
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!window.confirm(form.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  // Auto-submit de filtros em <select data-autosubmit>
  document.querySelectorAll('select[data-autosubmit]').forEach(function (sel) {
    sel.addEventListener('change', function () { sel.form && sel.form.submit(); });
  });

  // Soma viva dos pesos na matriz
  var weightInputs = document.querySelectorAll('[data-weight]');
  var weightSum = document.getElementById('weight-sum');
  if (weightInputs.length && weightSum) {
    var update = function () {
      var total = 0;
      weightInputs.forEach(function (i) { total += parseInt(i.value || 0, 10); });
      weightSum.textContent = total;
      weightSum.style.color = total === 100 ? 'inherit' : '#ff4438';
    };
    weightInputs.forEach(function (i) { i.addEventListener('input', update); });
    update();
  }
});
