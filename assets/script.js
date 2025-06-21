let isMenuOpen = false, mq
document.querySelectorAll('.collapsed-menu > li').forEach(el => {
  el.addEventListener('click', event => {
    document.querySelector('.right > .text-container').innerHTML = event.target.innerHTML
    if (window.matchMedia) {
      mq = window.matchMedia('(min-width: 675px)')
      onMediaChange(mq)
    }
  })
})
document.querySelector('.menu-toggle').addEventListener('click', () => {
  if (!isMenuOpen) {
    openMenu()
  } else {
    closeMenu()
  }
})

if (window.matchMedia) {
  mq = window.matchMedia('(min-width: 675px)')
  mq.addListener(onMediaChange)
}

function closeMenu() {
  document.querySelector('.container').style.transform = 'translateX(-300px)'
  isMenuOpen = false
}

function openMenu() {
  document.querySelector('.container').style.transform = 'translateX(0)'
  isMenuOpen = true
}

function onMediaChange(mq) {
  if (mq.matches) {
    document.querySelector('.container').style.transform = 'translateX(0)'
  } else {
    closeMenu()
  }
}
