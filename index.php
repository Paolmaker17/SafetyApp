<?php
include 'checkauth.php';
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="icon" type="image/png" href="">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <title>SafetyApp</title>
</head>

<body class="flex justify-center items-center bg-[var(--bg)] text-[var(--text)] inter select-none">

  <!-- Toggle Tema -->
  <div class="absolute left-3 top-3 cursor-pointer">
    <div
      class="flex items-center gap-2 bg-[var(--card)] border border-[var(--border)] px-3 py-2 rounded-2xl shadow-md transition hover:shadow-lg">

      <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px"
        fill="var(--text-light)" class="transition duration-300">
        <path
          d="M324-111.5Q251-143 197-197t-85.5-127Q80-397 80-480t31.5-156Q143-709 197-763t127-85.5Q397-880 480-880t156 31.5Q709-817 763-763t85.5 127Q880-563 880-480t-31.5 156Q817-251 763-197t-127 85.5Q563-80 480-80t-156-31.5ZM520-163q119-15 199.5-104.5T800-480q0-123-80.5-212.5T520-797v634Z" />
      </svg>

      <!-- Toggle -->
      <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="toggle" class="butt sr-only peer">

        <div class="butt w-14 h-7 bg-[var(--soft)] border border-[var(--border)] rounded-full 
                    peer-checked:bg-[var(--allarm)] 
                    transition-colors duration-300">
        </div>

        <div class="butt absolute left-1 top-1 w-5 h-5 bg-white rounded-full shadow-md 
                    transition-transform duration-300 
                    peer-checked:translate-x-7">
        </div>
      </label>
    </div>
  </div>

  <!-- User -->
  <div id="menu" class="absolute right-3 top-3 cursor-pointer">
    <div id="user"
      class="flex items-center gap-3 bg-[var(--card)] border border-[var(--border)] px-4 py-2 rounded-2xl shadow-md transition hover:shadow-lg">

      <img src="user-n.png" class="w-9 h-9 rounded-full border-2 border-[var(--border)] object-cover">

      <div class="flex flex-col">
        <span class="font-semibold text-sm">User Name</span>
        <span class="text-xs text-[var(--text-light)]">Online</span>
      </div>

      <svg class="w-4 h-4 text-[var(--text-light)] transition" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </div>

    <div id="dropdown"
      class="z-1 absolute right-0 mt-2 w-44 bg-[var(--card)] border border-[var(--border)] rounded-xl shadow-xl opacity-0 translate-y-2 pointer-events-none transition-all duration-200">
      <button
        class="w-full text-left px-4 py-3 text-sm font-semibold border-b border-[var(--border)] text-[var(--text-light)] hover:bg-[var(--soft)] rounded-t-xl transition">
        Profilo
      </button>

      <button
        class="w-full text-left px-4 py-3 text-sm font-semibold text-[var(--allarm-off-text)] hover:bg-[var(--allarm-off)]/10 rounded-b-xl transition">
        Logout
      </button>
    </div>
  </div>

  <div class="relative top-24 w-[85%] max-w-5xl flex flex-col items-center">

    <!-- Tabs -->
    <div class="flex border-b border-[var(--border)] text-[var(--text-light)] self-start w-full">
      <button disabled data-name="invioAllarme"
        class="tab disabled:translate-y-px px-6 py-2 bg-[var(--soft)] border border-[var(--border)] border-b-0 rounded-t-xl font-semibold enabled:hover:bg-[var(--tab-hover)] disabled:bg-[var(--card)] transition">
        Invio Allarme
      </button>

      <!-- <button data-name="allarmi"
        class="tab disabled:translate-y-px px-6 py-2 bg-[var(--soft)] border border-[var(--border)] border-b-0 rounded-t-xl font-semibold enabled:hover:bg-[var(--tab-hover)] disabled:bg-[var(--card)] transition">
        Allarmi
      </button> -->

      <button data-name="utenti"
        class="tab disabled:translate-y-px px-6 py-2 bg-[var(--soft)] border border-[var(--border)] border-b-0 rounded-t-xl font-semibold enabled:hover:bg-[var(--tab-hover)] disabled:bg-[var(--card)] transition">
        Utenti
      </button>
    </div>

    <div class="bg-[var(--card)] shadow-xl rounded-b-xl p-6 border border-t-0 border-[var(--border)] w-full">

      <!-- Content Invio Allarme -->
      <div class="flex gap-6" id="invioAllarme">
        <div class="flex flex-col">
          <form class="w-1/3 space-y-4 w-full" name="inviaForm">
            <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3">
              <input list="listAllarm" placeholder="Messaggio di Allarme" required type="text" id="messaggioInput"
                class="text-[var(--text-light)] font-semibold w-full p-3 rounded-xl border border-[var(--border)] focus:border-[var(--allarm)] focus:ring-4 focus:ring-[var(--allarm)]/20 outline-none transition bg-[var(--card)]">

              <datalist id="listAllarm">
                <option value="Allarme Terremoto"></option>
                <option value="Allarme Incendio"></option>
                <option value="Allarme Alluvione"></option>
                <option value="Allarme Generico"></option>
              </datalist>
            </div>

            <hr class="text-[var(--border)] w-full">

            <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3 flex flex-col items-center">
              <button type="submit"
                class="butt w-full font-extrabold h-18 bg-[var(--allarm)] hover:bg-[var(--allarm-hover)] text-white rounded-xl transition transform hover:-translate-y-1 hover:shadow-lg">
                Invia Allarme</button>

              <button type="button"
                id="stopAllarmeBtn"
                class="butt mt-3 w-full font-extrabold h-15 bg-[var(--allarm-stop)] hover:bg-[var(--allarm-hover-stop)] text-white rounded-xl transition transform hover:-translate-y-1 hover:shadow-lg">
                  Stop Allarme
              </button>
            </div>

          </form>
        </div>

        <div class="w-2/3 bg-[var(--soft)] border border-[var(--border)] rounded-xl p-4 h-64 flex flex-col">
          <label class="mb-2 font-semibold text-[var(--text-light)]">Descrizione</label>
          <textarea id="descInput" spellcheck="false" class="w-full 
                          h-full 
                          p-3 
                          resize-none 
                          rounded-xl 
                          border 
                          border-[var(--border)] 
                          focus:border-[var(--allarm)] 
                          focus:ring-4 
                          focus:ring-[var(--allarm)]/20 
                          outline-none 
                          transition 
                          bg-[var(--card)]
                          select-text
                          selection:bg-[var(--border)]"></textarea>
        </div>
      </div>

      <!-- Content Allarmi 
      <div class="hidden gap-6" id="allarmi">
        <div class="w-1/2 space-y-4 pt-1">
          <div class="w-full bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3 h-32 flex flex-col">
            <label class="mb-2 font-semibold text-[var(--text-light)]">Nome Nuovo Allarme</label>
            <textarea spellcheck="false" class="w-full 
                            h-full 
                            p-3
                            resize-none 
                            rounded-xl 
                            border 
                            border-[var(--border)] 
                            focus:border-[var(--allarm)] 
                            focus:ring-4 
                            focus:ring-[var(--allarm)]/20 
                            outline-none 
                            transition 
                            bg-[var(--card)]
                            select-text
                            selection:bg-[var(--border)]"></textarea>
          </div>

          <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3 h-24 flex items-center">
            <button class="butt w-full font-extrabold h-full bg-[var(--allarm)] hover:bg-[var(--allarm-hover)] text-white rounded-xl transition transform hover:-translate-y-1 hover:shadow-lg">
            Aggiungi Tipologia Allarme</button>
          </div>
        </div>

        <div class="w-1/2 space-y-4 border-l-2 border-dashed border-[var(--border)] p-6 pb-8 pt-1 pr-0">
          <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3">
            <select class="text-[var(--text-light)] font-semibold w-full p-3 rounded-xl border border-[var(--border)] focus:border-[var(--allarm)] focus:ring-4 focus:ring-[var(--allarm)]/20 outline-none transition bg-[var(--card)]">
              <option value="1">Allarme Terremoto</option>
              <option value="2">Allarme Incendio</option>
              <option value="3">Allarme Alluvione</option>
              <option value="4" selected>Allarme Generico</option>
            </select>
          </div>

          <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3 h-24 flex items-center">
            <button class="butt w-full font-extrabold h-full bg-[var(--allarm)] hover:bg-[var(--allarm-hover)] text-white rounded-xl transition transform hover:-translate-y-1 hover:shadow-lg">
            Rimuovi Tipologia Allarme</button>
          </div>
        </div>
      </div>-->

      <!-- Content Utenti-->
      <div class="hidden gap-6" id="utenti">
        <div class="w-[50%]">
          <!-- Aggiungi Utente -->
          <form name="addUserForm">
            <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3">
              <input placeholder="Username da Aggiungere" required type="text" id="addUsername"
                  class="text-[var(--text-light)] font-semibold w-full p-3 rounded-xl border border-[var(--border)] focus:border-[var(--allarm)] focus:ring-4 focus:ring-[var(--allarm)]/20 outline-none transition bg-[var(--card)]">
              
              <input placeholder="Password" required type="password" id="addPassword"
                  class="mt-3 text-[var(--text-light)] font-semibold w-full p-3 rounded-xl border border-[var(--border)] focus:border-[var(--allarm)] focus:ring-4 focus:ring-[var(--allarm)]/20 outline-none transition bg-[var(--card)]">
            
              <input type="submit" value="Aggiungi Utente"
                  class="butt mt-3 w-full font-extrabold h-15 bg-[var(--allarm)] hover:bg-[var(--allarm-hover)] text-white rounded-xl transition transform hover:-translate-y-1 hover:shadow-lg">
            </div>
          </form>

        </div>

        <div class="w-[50%]">
          <!-- Rimuovi Utente -->
          <form name="deleteUserForm">
            <div class="bg-[var(--soft)] border border-[var(--border)] rounded-xl p-3">
              <input placeholder="Username da Eliminare" required type="text" id="deleteUsername"
                  class="text-[var(--text-light)] font-semibold w-full p-3 rounded-xl border border-[var(--border)] focus:border-[var(--allarm)] focus:ring-4 focus:ring-[var(--allarm)]/20 outline-none transition bg-[var(--card)]">
              
              <input placeholder="Password" required type="password" id="deletePassword"
                  class="mt-3 text-[var(--text-light)] font-semibold w-full p-3 rounded-xl border border-[var(--border)] focus:border-[var(--allarm)] focus:ring-4 focus:ring-[var(--allarm)]/20 outline-none transition bg-[var(--card)]">
            
              <input type="submit" value="Rimuovi Utente"
                  class="butt mt-3 w-full font-extrabold h-15 bg-[var(--allarm-stop)] hover:bg-[var(--allarm-hover-stop)] text-white rounded-xl transition transform hover:-translate-y-1 hover:shadow-lg">
            </div>
          </form>

        </div>
      </div>

    </div>

    <!-- Stato Allarme -->
    <div
      class="text-(--text-status) top-12 w-130 max-w-full bg-[var(--card)] shadow-xl rounded-2xl p-4 border border-[var(--border)] mt-10">
      <div class="flex items-center gap-3 h-min">
        <div id="statusDot" class="w-3 h-3 bg-gray-500 rounded-full m-1"></div>
        <div class="w-px self-stretch bg-(--text-light)"></div>
        <div class="flex-1 flex flex-col">
          <div id="statusMessage" class="text-xl font-semibold">Connessione in corso al server</div>
          <div id="statusDesc" class="text-(--text-light)">Lo stato sarà aggiornato a breve</div>
        </div>
      </div>
    </div>

  </div>

  <!-- Pop Up Window -->
  <div id="popUpWindow" class="fixed right-4 bottom-10 w-80 rounded-2xl overflow-hidden
          bg-(--card)/70 backdrop-blur-md
          border border-(--border) shadow-xl
          opacity-0 translate-y-6 scale-95
          transition-all duration-300 ease-out
          hover:scale-[1.02] hover:shadow-2xl
          transition-transform">

    <div class="absolute top-0 left-0 w-full h-1 bg-black/10">
      <div id="countdownBar" class="h-full bg-(--allarm) w-full">
      </div>
    </div>

    <div class="flex items-center gap-3 p-4 pt-5">
      <div class="shrink-0">
        <svg width="35" height="35" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">

          <circle cx="32" cy="32" r="28" stroke="#22c55e" stroke-width="4" fill="none" stroke-dasharray="176"
            stroke-dashoffset="176">
            <animate attributeName="stroke-dashoffset" from="176" to="0" dur="0.4s" fill="freeze" />
          </circle>

          <path d="M20 34 L28 42 L44 24" stroke="#22c55e" stroke-width="5" stroke-linecap="round"
            stroke-linejoin="round" stroke-dasharray="40" stroke-dashoffset="40">
            <animate attributeName="stroke-dashoffset" from="40" to="0" dur="0.3s" begin="0.4s" fill="freeze" />
          </path>

        </svg>
      </div>

      <div class="flex-1">
        <h3 class="font-semibold text-(--text-status)" id="popUpWindowText">
          Operazione riuscita
        </h3>
      </div>

      <button id="closePopupBut" class="text-(--text-muted)
              hover:text-(--text-status)
              text-xl font-bold
              w-6 h-6 flex items-center justify-center
              rounded-lg hover:bg-black/10
              transition">
        ✕
      </button>

    </div>
  </div>

  <script src="index.js"></script>
  <script src="theme.js"></script>
</body>

</html>