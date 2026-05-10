<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Cấu trúc thư mục của dự án

laravel-project/    
├── app/ NƠI LÀM VIỆC CHÍNH: Chứa logic cốt lõi của ứng dụng   
│   ├── Http/ Xử lý giao diệnweb (Controllers, Middleware)  
│   ├── Models/ Tương tác Database (Eloquent Models)    
│   ├── Console/ Câu lệnh Artisan tùy chỉnh     
│   ├── Providers/ Khởi tạo dịch vụ cho ứng dụng    
│   └── ... (Broadcasting, Exceptions, Jobs, Listeners, Policies...)    
│   
├── bootstrap/ Khởi động framework và cache hiệu năng   
├── config/ Cấu hình ứng dụng (database, app, mail...)  
├── database/ Quản lý CSDL (Migrations, Seeders, Factories)     
│   
├── public/ CỬA NGÕ DUY NHẤT ra internet (index.php, CSS, JS)   
│   
├── resources/ Giao diện & tài nguyên thô       
│   ├── views/ Template Blade (.blade.php)      
│   ├── css/ & js/ File nguồn (chạy qua Vite)       
│   
│   
├── routes/ Định nghĩa URL  
│   ├── web.php: Route cho giao diện web    
│   ├── api.php: Route cho API (cần cài đặt riêng)  
│   └── console.php: Định nghĩa câu lệnh Artisan    
│   
├── storage/ File hệ thống (log, cache, session, file upload)   
├── tests/ Code kiểm thử (Unit Test)    
├── vendor/ Thư viện composer (Không sửa thủ công)  
├── .env: file biến môi trường      
├── .env.example: file biến môi trường mẫu, phục vụ cho cài đặt trên container (Vui lòng không chỉnh sửa thủ công)  
└── README.md: Tài liệu hướng dẫn

## Phòng tránh xung đột giữa branch của mình và main (trong trường hợp main đã có những commit mới)
Trước khi yêu cầu hợp nhất branch của mình và main, hãy commit tất cả chỉnh sửa lên branch của mình trước rồi làm các bước sau:
1. Sang branch main: `git checkout main`
2. Pull các commit mới về main của mình: `git pull origin main`
3. Về lại branch của mình: `git checkout tên_branch_của_mình`
4. Merge main với branch của mình: `git merge main`
5. Nếu xảy ra xung đột, hãy giải quyết xung đột (hoặc là hỏi nhóm trưởng để cùng sửa):
   - Mở file bị xung đột lên, tìm các đoạn có `<<<<<<<`, `=======`, `>>>>>>>`
   - Quyết định giữ phần code nào (của bạn hay của main, hoặc cả hai)
   - Xóa các dòng `<<<<<<<`, `=======`, `>>>>>>>>`
   - Lưu file lại
6. Hoàn tất merge sau khi giải quyết xung đột 

```
git add .
git commit -m "Resolve merge conflicts"
git push origin ten_branch_cua_ban
```

7. Lúc này branch của bạn đã an toàn để tạo Merge Request