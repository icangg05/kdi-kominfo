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

// document.addEventListener("livewire:load", () => {
//   Livewire.hook("message.processed", () => {
//     setTimeout(() => {
//       HSOverlay?.autoInit?.();
//     }, 100);
//   });
// });
