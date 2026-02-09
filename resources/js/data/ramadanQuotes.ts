export interface RamadanQuote {
    text: string;
    surah: string;
}

export const ramadanQuotes: RamadanQuote[] = [
    {
        text: "Wahai orang-orang yang beriman! Diwajibkan atas kamu berpuasa sebagaimana diwajibkan atas orang sebelum kamu agar kamu bertakwa.",
        surah: "QS. Al-Baqarah: 183"
    },
    {
        text: "Dan apabila hamba-hamba-Ku bertanya kepadamu tentang Aku, maka sesungguhnya Aku dekat.",
        surah: "QS. Al-Baqarah: 186"
    },
    {
        text: "Bulan Ramadan adalah bulan yang di dalamnya diturunkan Al-Qur'an, sebagai petunjuk bagi manusia.",
        surah: "QS. Al-Baqarah: 185"
    },
    {
        text: "Sesungguhnya sesudah kesulitan itu ada kemudahan.",
        surah: "QS. Al-Insyirah: 6"
    },
    {
        text: "Dan bersabarlah, sesungguhnya Allah beserta orang-orang yang sabar.",
        surah: "QS. Al-Anfal: 46"
    },
    {
        text: "Maka ingatlah kepada-Ku, niscaya Aku akan mengingat kamu.",
        surah: "QS. Al-Baqarah: 152"
    },
    {
        text: "Sesungguhnya Allah tidak akan mengubah keadaan suatu kaum hingga mereka mengubah keadaan diri mereka sendiri.",
        surah: "QS. Ar-Ra'd: 11"
    },
    {
        text: "Dan Tuhanmu berfirman, 'Berdoalah kepada-Ku, niscaya akan Aku perkenankan bagimu.'",
        surah: "QS. Ghafir: 60"
    },
    {
        text: "Barang siapa mengerjakan kebaikan sebesar zarrah, niscaya dia akan melihat balasannya.",
        surah: "QS. Az-Zalzalah: 7"
    },
    {
        text: "Hai orang-orang yang beriman, mohonlah pertolongan kepada Allah dengan sabar dan shalat.",
        surah: "QS. Al-Baqarah: 153"
    },
    {
        text: "Sesungguhnya Kami menurunkannya pada malam kemuliaan.",
        surah: "QS. Al-Qadr: 1"
    },
    {
        text: "Dan hanya kepada Tuhanmulah hendaknya kamu berharap.",
        surah: "QS. Al-Insyirah: 8"
    },
    {
        text: "Maka sesungguhnya bersama kesulitan ada kemudahan.",
        surah: "QS. Al-Insyirah: 5"
    },
    {
        text: "Dan orang-orang yang berjihad di jalan Kami, sungguh akan Kami tunjukkan kepada mereka jalan-jalan Kami.",
        surah: "QS. Al-Ankabut: 69"
    },
    {
        text: "Dan barang siapa bertawakal kepada Allah, niscaya Allah akan mencukupkan keperluannya.",
        surah: "QS. At-Talaq: 3"
    },
    {
        text: "Wahai jiwa yang tenang! Kembalilah kepada Tuhanmu dengan hati yang ridha dan diridhai-Nya.",
        surah: "QS. Al-Fajr: 27-28"
    },
    {
        text: "Allah tidak membebani seseorang melainkan sesuai dengan kesanggupannya.",
        surah: "QS. Al-Baqarah: 286"
    },
    {
        text: "Sesungguhnya shalat itu mencegah dari perbuatan keji dan mungkar.",
        surah: "QS. Al-Ankabut: 45"
    },
    {
        text: "Wahai orang-orang yang beriman! Bertakwalah kepada Allah dan carilah jalan untuk mendekatkan diri kepada-Nya.",
        surah: "QS. Al-Ma'idah: 35"
    },
    {
        text: "Berlomba-lombalah kamu dalam kebaikan.",
        surah: "QS. Al-Baqarah: 148"
    },
    {
        text: "Sesungguhnya yang paling mulia di antara kamu di sisi Allah ialah orang yang paling bertakwa.",
        surah: "QS. Al-Hujurat: 13"
    },
    {
        text: "Dan janganlah kamu berputus asa dari rahmat Allah.",
        surah: "QS. Az-Zumar: 53"
    },
    {
        text: "Hai orang-orang yang beriman, bertakwalah kepada Allah dan hendaklah setiap diri memperhatikan apa yang telah diperbuatnya untuk hari esok.",
        surah: "QS. Al-Hasyr: 18"
    },
    {
        text: "Dan mohonlah ampunan kepada Allah. Sungguh, Allah Maha Pengampun, Maha Penyayang.",
        surah: "QS. Al-Muzzammil: 20"
    },
    {
        text: "Sungguh, Kami telah menciptakan manusia dalam bentuk yang sebaik-baiknya.",
        surah: "QS. At-Tin: 4"
    },
    {
        text: "Dan rendahkanlah dirimu terhadap keduanya dengan penuh kasih sayang.",
        surah: "QS. Al-Isra: 24"
    },
    {
        text: "Cukuplah Allah bagiku, tidak ada Tuhan selain Dia. Hanya kepada-Nya aku bertawakal.",
        surah: "QS. At-Taubah: 129"
    },
    {
        text: "Sesungguhnya di dalam penciptaan langit dan bumi, dan pergantian malam dan siang terdapat tanda-tanda bagi orang yang berakal.",
        surah: "QS. Ali 'Imran: 190"
    },
    {
        text: "Wahai orang-orang yang beriman! Jadilah kamu penegak keadilan, menjadi saksi karena Allah.",
        surah: "QS. An-Nisa: 135"
    },
    {
        text: "Dan Dia bersama kamu di mana saja kamu berada. Dan Allah Maha Melihat apa yang kamu kerjakan.",
        surah: "QS. Al-Hadid: 4"
    }
];

export function getTodayQuote(): RamadanQuote {
    const now = new Date();
    const start = new Date(now.getFullYear(), 0, 0);
    const diff = now.getTime() - start.getTime();
    const dayOfYear = Math.floor(diff / (1000 * 60 * 60 * 24));
    return ramadanQuotes[dayOfYear % ramadanQuotes.length];
}
