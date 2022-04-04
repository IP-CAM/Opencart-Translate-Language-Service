const messages = {
  en: {
    html_encode: {
      remove_tags: 'Tags to remove',
      instructions_title:'Instructions',
      instructions:'Many wysiwyg editors save html encoded texts. Using this functionality, the product descriptions are scanned in the database and when we have html encoded text, it decodes it. Also in case we select some of the html tags, they are removed along with the decode process.',
      progress: 'Progress',
      decode_all: 'Decode All',
      decode_all_tooltip: 'Decode html from all product descriptions.',
      products_parsed: ' products descriptions decoded! total ',
    }
  },
  el: {
    html_encode: {
      remove_tags: 'Διαγραφή tags',
      instructions_title:'Οδηγίες',
      instructions:'Πολλοί wysiwyg editors αποθηκεύουν τα κείμενα html encoded. Χρησιμποποιώντας αυτή τη λειτουργικότητα, σκανάρονται οι περιγραφές των προϊόντων στη βάση και όταν έχουμε html encoded κείμενο, το κάνει decode. Επίσης σε περίπτωση που επιλέξουμε και κάποια από τα html tags, αυτά αφαιρούνται μαζί με τη διαδικασία του decode.',
      progress: 'Αποτελέσματα',
      decode_all: 'Εκτέλεση',
      decode_all_tooltip: 'Decode html από όλες τις περιγραφές προϊόντων.',
      products_parsed: ' περιγραφές προϊόντων έγιναν decode! Σύνολο ',
    }
  }
}

// Create VueI18n instance with options
export default new VueI18n({
  locale: document.documentElement.lang, // set locale
  messages, // set locale messages
})
