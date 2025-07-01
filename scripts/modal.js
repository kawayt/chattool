const openButton = document.getElementById('openButton');
const modalDialog = document.getElementById('modalDialog');

// モーダルを開く
openButton?.addEventListener('click', async () => {
  modalDialog.showModal();
  // モーダルダイアログを表示する際に背景部分がスクロールしないようにする
  document.documentElement.style.overflow = "hidden";
});

const closeButton = document.getElementById('closeButton');

// モーダルを閉じる
closeButton?.addEventListener('click', () => {
  modalDialog.close();
  // モーダルを解除すると、スクロール可能になる
  document.documentElement.removeAttribute("style");
});

// 背景をクリックしてモーダルを閉じる
modalDialog.addEventListener('click', (event) => {
  if (event.target.closest('#dialogInputArea') === null) {
    modalDialog.close();
  }
});