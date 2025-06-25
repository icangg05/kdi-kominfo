import "./bootstrap";
import "./preline";

Fancybox.bind("[data-fancybox]", {
  Hash: false,
});

document.addEventListener("livewire:navigated", () => {
  window.HSStaticMethods?.autoInit?.();

  Fancybox.bind("[data-fancybox]", {
    Hash: false,
  });
});
