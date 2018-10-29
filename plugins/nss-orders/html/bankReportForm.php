<div class="Header">
    <div>
        <h3>Dodajte izvestaj od banke</h3>
        <p style="font-size:12px">
            1. Kliknite Browse i sa svog računara izaberite izvod iz banke u tekstualnom formatu. <br>
            2. Kliknite Submit kako bi uploadovali izvod u sistem. Sistem je povezao uplate sa narudžbenicama. Prikazane uplate nisu povezane sa narudžbenicama. <br>
            3. Odčekirajte narudžbenice koje ne pripadaju NSS-u. <br>
            4. Preostale uplate potrebno je povezati ručno.  <br>
            (napomena: narudžbenice plaćene pouzećem kada stigne uplata dobijaju status Finalizovano, narudžbenice sa plaćanjem Uplatnicom ili eBankingom po prispeću uplate dobijaju status U PRIPREMI PLAĆENO) <br>
            Kada se narudžbenica ručno poveže pored za nju treba čekirati opciju Ručno povezivanje (Match). <br>
            5. Svakoga dana treba počistiti sve uplate.
        </p>
        <form action="admin.php?page=nss-orders&tab=bankReportAction" method="post" enctype="multipart/form-data">
            <input type="file" name="bankReportFile">
            <input type="submit" value="Posalji">
        </form>
    </div>
</div>
