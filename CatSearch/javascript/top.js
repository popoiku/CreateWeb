// html読み込み後に実行(画像読み込み前)
document.addEventListener("DOMContentLoaded", () => {
  
  // フォーム書き込み履歴の削除
  onpageshow = () => {
    document.getElementById("f1").reset();
  }

  // 送信ボタン制御
  searchcon();

  // topへ戻るボタン
  backtotop();

});


const searchcon = () => {

  const submitControl = document.getElementById("submit-image");
  submitControl.disabled = true;

  const searchForm = document.getElementById("search_field");

  searchForm.addEventListener("input", () => {
    const stValue = event.target.value;
    const stCheck = stValue.replace(/\s+/g,"").length;

    if (stCheck !== 0) {
      submitControl.disabled = false;
    } else {
      submitControl.disabled = true;
    }
  });
}


const backtotop = () => {

  const clickback = document.getElementById("return_top");

  clickback.addEventListener("click", (e) => {
    anime.remove("html, body");
    anime({
      targets: "html, body",
      scrollTop: 0,
      dulation: 600,
      easing: 'easeOutCubic',
    });
    return false;
  });

  


}
