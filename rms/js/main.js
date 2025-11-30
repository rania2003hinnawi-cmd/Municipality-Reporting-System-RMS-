// main.js - وظائف تحسينية لمشروع شكاوي البلدية

document.addEventListener('DOMContentLoaded', function() {

    // 1. تأكيد الحذف
    document.querySelectorAll('.confirm-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                e.preventDefault();
            }
        });
    });

    // 2. تأكيد الخروج
    document.getElementById('logout-link')?.addEventListener('click', function(e) {
        if (!confirm('هل تريد بالتأكيد تسجيل الخروج؟')) {
            e.preventDefault();
        }
    });

    // 3. التحقق من حجم الصورة
    document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                alert('حجم الصورة كبير جدًا! يُرجى اختيار صورة أقل من 2 ميجابايت.');
                e.target.value = '';
            }
        });
    });

    // 4. إخفاء الرسائل الناجحة تلقائيًا
    const successMsg = document.querySelector('.success');
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.transition = 'opacity 0.5s';
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 500);
        }, 3000);
    }

    // 5. منع إرسال النموذج مرتين
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'جاري المعالجة...';
            }
        });
    });

});