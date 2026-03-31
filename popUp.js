let duration = 5000
let timer
let startTime
let remaining = duration

function startTimer() {
  startTime = Date.now()

  countdownBar.style.transition = "none"
  countdownBar.style.width = "100%"

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      countdownBar.style.transition = `width ${remaining}ms linear`
      countdownBar.style.width = "0%"
    })
  })

  timer = setTimeout(closePopup, remaining)
}

function pauseTimer() {
  clearTimeout(timer)

  const elapsed = Date.now() - startTime
  remaining -= elapsed

  const percentLeft = (remaining / duration) * 100
  countdownBar.style.transition = "none"
  countdownBar.style.width = percentLeft + "%"
}

function resetTimer() {
  clearTimeout(timer)
  remaining = duration

  countdownBar.style.transition = "none"
  countdownBar.style.width = "100%"
}

function closePopup() {
  popUpWindow.classList.add("translate-y-6", "opacity-0", "scale-95")
  setTimeout(() => popUpWindow.style.display = "hidden", 300)
}

popUpWindow.addEventListener("mouseenter", () => {
  pauseTimer()
  resetTimer()
})
popUpWindow.addEventListener("mouseleave", () => {
  remaining = duration
  startTimer()
})
closePopupBut.addEventListener("click", closePopup)


function showToast(text) {
  document.getElementById('popUpWindowText').textContent = text;
  setTimeout(() => {
    popUpWindow.classList.remove('translate-y-10', 'opacity-0')
  }, 100)
  startTimer()
}