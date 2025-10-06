'use strict';

document.addEventListener("DOMContentLoaded", () => {
  const main = document.getElementById("main-image");
  const thumbnails = document.querySelectorAll(".thumbnail");

  thumbnails.forEach((thumb) => {
    thumb.addEventListener("click", () => {
      // 画像サムネイルの場合
      if (thumb.tagName === "IMG") {
        if (main.tagName === "VIDEO") {
          // videoから画像に戻す
          const newImg = document.createElement("img");
          newImg.id = "main-image";
          newImg.classList.add("main-photo");
          newImg.src = thumb.src;
          newImg.alt = "投稿画像";
          main.replaceWith(newImg);
        } else {
          main.src = thumb.src;
        }
      }

      // 動画サムネイルの場合
      if (thumb.tagName === "VIDEO") {
        const videoSrc = thumb.querySelector("source").src;
        const video = document.createElement("video");
        video.id = "main-image";
        video.classList.add("main-photo");
        video.controls = true;
        video.autoplay = true;
        video.muted = true;

        const source = document.createElement("source");
        source.src = videoSrc;
        source.type = "video/mp4";
        video.appendChild(source);

        main.replaceWith(video);
      }
    });
  });
});
