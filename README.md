### 1. Đăng nhập/đăng xuất thông qua SSO
- Trang sso tại địa chỉ `https://id.aipacific.vn/sso/SSO/`
- Trang chứng thực và phân quyền tập trung tại địa chỉ `https://jwt.aipacific.vn`
- Các ứng dụng cần chứng thực SSO có thể theo kiểu Sessionless hoặc theo kiểu dùng Session nội bộ (như thông thường).
#### 1.1. Kiểu Session thông thường
- Kiểu này ứng dụng chỉ sử dụng SSO như một cổng để login, logout. Phần phân quyền vẫn nằm nội bộ trên ứng dụng chứ không qua hệ thống phân quyền tập trung.
- Giả sử ứng dụng [A] cần authen dùng SSO. Lồng xử lý đăng nhập trên web (trên App sẽ khác) sẽ như sau: **User truy cập ứng dụng [A] --> [A] redirect qua SSO đăng nhập --> SSO thực hiện đăng nhập --> đẩy kết quả cùng user-token về cho [A]. Việc còn lại [A] tự lo.**
- Trong user-token trả về từ SSO có thông tin user gồm địa chỉ email. [A] sẽ dùng email này để map vào với user nội bộ của [A]
#### 1.2. Kiểu Sessionless
- Phiên xử lý authentication sẽ gọi SSO như 1.1
- Tất cả các phiên xử lý của ứng dụng đều phải thực hiện authorization qua JWT dựa trên user-token đã được cấp.
- User-state sẽ ưu tiên lưu ở local-storage của client-side theo từng router của SPA
#### 1.3. Các tham số khi gọi SSO và kết quả trả về từ SSO
- đường dẫn cụ thể `https://id.aipacific.vn/sso/SSO/?_act=authen`. Đồng thời cần thêm 2 tham số như mô tả sau:
- `redirect`: sau khi SSO chứng thực thành công sẽ redirect tới (tham số bắt buộc)
- `callback`: nếu người dùng không muốn thực hiện chứng thực nữa sẽ trở lại trang này (tham số này optional)
- các tham số này có thể truyền qua POST hoặc GET
- giá trị của cả 2 tham số trên cần được mã (1 lần) bằng base64
- ví dụ (PHP) `'https://id.aipacific.vn/sso/SSO/?_act=authen&redirect='.base64_encode('abc.com/dashboard').'&callback='.base64_encode('abc.com')`. Sử dụng link này để target tới SSO. SSO thực hiện chứng thực thành công sẽ gọi ngược lại như sau `abc.com/dashboard&token=<user-token được cấp>`
### 2. Gắn user-token và app-token (server-side, client-side, mobile) khi truy xuất
- Các phiên truy xuất (server <-> server hoặc client <-> server) đều phải được gắn user-token vào header như sau:
`Authorization: Bearer <user-token được cấp>`. Ví dụ `Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2p3dCIsImF1ZCI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJqdGkiOiI0ZjFnMjNhMTJhYSIsImlhdCI6IjE1MzU1MTM2zM1NTgxIiwibmJmIjoiMTUzNTUxMzc1MC43MzU1ODEiLCJleHiIxNTY3MDQ5NjkwLjczNTU4MSIsIklkIjoiMTU5OSIsIk5hbWUiOiLEkMOgbyBOZ-G7jWMgR2lhbmcifQ.rNF8FbsmX7fgdOxXZzIObTPR4AaE8N9ciSCck3R3YIE`

### 3. Việc sử dụng public-key và keys-pair
- Đảm bảo token toàn vẹn và không ai có thể thay đổi khi truyền 
### 4. Việc gắn client fingerprint
- Loại bỏ việc dùng cookie và đảm bảo trộm token cũng không dùng được 
