

export default function useEncryptDecrypt() {
    function encryptID(textID) {
      return btoa(textID);
    }
    function decryptID(encryptedID, action=null) {
      const result= atob(encryptedID); 
      // alert(action);
      // if(action == result.split("-")[1]){
      //   return result.split("-")[0];
      // }
      return result.split("-")[0];
    }

    function encrypt(plaintext, id) {
      const textID = id + '-' + plaintext;
      const encryptedID = encryptID(textID);

      const ciphertext = plaintext
          .split('')
          .map(char => {
              const code = char.charCodeAt(0);
              if (code >= 65 && code <= 90) {
                  return String.fromCharCode(((code - 65 + id) % 26) + 65);
              } else if (code >= 97 && code <= 122) {
                  return String.fromCharCode(((code - 97 + id) % 26) + 97);
              } else {
                  return char;
              }
          })
          .join('');

      return ciphertext + '-' + encryptedID;
    }

    function decrypt(ciphertext) {
      const encryptedID = ciphertext.split("-")[1];
      return decryptID(encryptedID);
    }
    return {
      encrypt,
      decrypt
    };
  }