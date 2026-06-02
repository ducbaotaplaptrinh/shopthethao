# QUY TRÌNH GIT LÀM VIỆC NHÓM (2 NGƯỜI)

## Cấu trúc nhánh

```text
main      : Code ổn định
ducbao    : Nhánh của bạn
ducdat    : Nhánh của em bạn
```

---

# 1. Tạo nhánh

## Tạo nhánh của bạn

```bash
git checkout main
git pull origin main
git checkout -b ducbao
git push -u origin ducbao
```

### Tác dụng

- Tạo nhánh riêng để làm việc.
- Không ảnh hưởng đến main.
- Các lần sau chỉ cần `git push`.

---

## Tạo nhánh cho em bạn

```bash
git checkout main
git pull origin main
git checkout -b ducdat
git push -u origin ducdat
```

### Tác dụng

- Tạo khu vực làm việc riêng cho em bạn.
- Tránh sửa trực tiếp trên main.

---

# 2. Clone dự án

```bash
git clone <repository-url>
```

Ví dụ:

```bash
git clone https://github.com/abc/Shop_TheThao.git
```

### Tác dụng

- Tải toàn bộ source code về máy.

---

# 3. Chuyển sang nhánh cần làm

## Bạn

```bash
git checkout ducbao
```

## Em bạn

```bash
git checkout ducdat
```

### Tác dụng

- Đảm bảo code trên đúng nhánh của từng người.

---

# 4. Mỗi lần bắt đầu làm việc

## Bạn

```bash
git checkout ducbao
git pull
```

## Em bạn

```bash
git checkout ducdat
git pull
```

### Tác dụng

- Lấy code mới nhất từ GitHub.
- Tránh xung đột khi làm việc.

---

# 5. Sau khi code xong

## Kiểm tra file thay đổi

```bash
git status
```

### Tác dụng

- Xem file nào đã sửa.
- Xem file nào chưa được Git theo dõi.

---

## Thêm file vào vùng chuẩn bị commit

```bash
git add .
```

### Tác dụng

- Đưa tất cả file thay đổi vào Staging Area.

---

## Tạo commit

```bash
git commit -m "Thêm chức năng đăng ký"
```

### Tác dụng

- Lưu lại một phiên bản code.

---

## Đẩy code lên GitHub

```bash
git push
```

### Tác dụng

- Đồng bộ code lên GitHub.

---

# 6. Merge vào main

## Chuyển sang main

```bash
git checkout main
```

## Cập nhật main

```bash
git pull origin main
```

## Merge

```bash
git merge ducbao
```

hoặc

```bash
git merge ducdat
```

## Push main

```bash
git push origin main
```

### Tác dụng

- Đưa chức năng đã hoàn thành vào bản chính thức.

---

# 7. Sau khi main có code mới

Ví dụ bạn vừa merge lên main.

Em bạn cần cập nhật:

```bash
git checkout ducdat
git fetch origin
git merge origin/main
```

### Tác dụng

- Lấy toàn bộ cập nhật mới từ main.

---

# 8. Kiểm tra nhánh hiện tại

```bash
git branch
```

Ví dụ:

```text
* ducbao
  main
  ducdat
```

### Tác dụng

- Dấu \* cho biết đang đứng ở nhánh nào.

---

## Cách ngắn gọn

```bash
git branch --show-current
```

---

# 9. Xử lý khi Git không cho đổi nhánh

Thông báo:

```text
Your local changes would be overwritten by checkout
```

## Cách 1: Commit

```bash
git add .
git commit -m "Lưu tạm"
```

## Cách 2: Stash

```bash
git stash -u
```

Khôi phục:

```bash
git stash pop
```

### Tác dụng

- Lưu tạm thay đổi chưa commit.

---

# QUY TRÌNH HẰNG NGÀY

## Bạn

```bash
git checkout ducbao
git pull

... code ...

git add .
git commit -m "Mô tả chức năng"
git push
```

## Em bạn

```bash
git checkout ducdat
git pull

... code ...

git add .
git commit -m "Mô tả chức năng"
git push
```

## Khi hoàn thành chức năng

```bash
git checkout main
git pull origin main
git merge <ten-nhanh>
git push origin main
```

Ví dụ:

```bash
git merge ducbao
```
